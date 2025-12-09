@extends('layouts.dashboard')
@section('title', 'Jadwal Pelajaran')
@section('header', 'Pengaturan Jadwal Pelajaran')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-4 gap-6" x-data="scheduleManager()">
    <!-- Sidebar Filters & Tools -->
    <div class="bg-white p-6 rounded-xl shadow-md h-fit">
        <h3 class="font-bold mb-4">Filter & Tools</h3>
        
        <div class="mb-4">
             <a href="{{ route('academic.time-settings.index') }}" class="text-blue-600 text-sm hover:underline">
                 Atur Jam Pelajaran (Bell Schedule)
             </a>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Tahun Akademik</label>
            <select x-model="filters.academic_year_id" @change="refetch" class="w-full border rounded px-3 py-2">
                <option value="">Pilih Tahun Akademik</option>
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}">{{ $year->name }} {{ $year->is_active ? '(Aktif)' : '' }}</option>
                @endforeach
            </select>
        </div>


        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Kelas</label>
            <select x-model="filters.classroom_id" @change="refetch" class="w-full border rounded px-3 py-2" :disabled="!filters.academic_year_id && !filters.teacher_id">
                <option value="">Pilih Kelas</option>
                @foreach($classrooms as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-bold mb-2">Guru</label>
            <select x-model="filters.teacher_id" @change="refetch" class="w-full border rounded px-3 py-2">
                <option value="">Pilih Guru (Opsional)</option>
                @foreach($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->user->name }}</option>
                @endforeach
            </select>
        </div>

        <hr class="my-4">
        
        <button @click="openModal('create')" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            + Tambah Jadwal
        </button>
    </div>

    <!-- Calendar Area -->
    <div class="col-span-1 md:col-span-3 bg-white p-6 rounded-xl shadow-md">
        <div id="schedule-calendar"></div>
    </div>

    <!-- Modal Form -->
    <x-modal name="schedule-modal" focusable>
        <div class="bg-white p-6 rounded-lg w-full">
            <h2 class="text-xl font-bold mb-4" x-text="mode === 'create' ? 'Tambah Jadwal' : 'Edit Jadwal'"></h2>
            
            <form @submit.prevent="submit">
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Mata Pelajaran (Course)</label>
                    <select x-model="form.course_id" class="w-full border rounded px-3 py-2" required>
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">
                                {{ $course->subject->name }} - {{ $course->classroom->name }} ({{ $course->teacher->user->name }})
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Pilih Course yang sudah menghubungkan Guru, Mapel, dan Kelas.</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Hari</label>
                    <select x-model="form.day_of_week" @change="fetchPeriods" class="w-full border rounded px-3 py-2" required>
                        <option value="monday">Senin</option>
                        <option value="tuesday">Selasa</option>
                        <option value="wednesday">Rabu</option>
                        <option value="thursday">Kamis</option>
                        <option value="friday">Jumat</option>
                        <option value="saturday">Sabtu</option>
                        <option value="sunday">Minggu</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4" x-show="activePeriods.length > 0">
                    <div>
                        <label class="block text-sm font-bold mb-2">Mulai Jam Ke-</label>
                        <select x-model="form.start_period" class="w-full border rounded px-3 py-2" required>
                            <option value="">Pilih Jam</option>
                            <template x-for="p in activePeriods" :key="p.id">
                                <option :value="p.period_number" x-text="p.label + ' (' + p.start_time.slice(0,5) + ')'"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold mb-2">Sampai Jam Ke-</label>
                        <select x-model="form.end_period" class="w-full border rounded px-3 py-2" required>
                            <option value="">Pilih Jam</option>
                            <template x-for="p in activePeriods" :key="p.id">
                                <option :value="p.period_number" x-text="p.label + ' (' + p.end_time.slice(0,5) + ')'"></option>
                            </template>
                        </select>
                    </div>
                </div>
                <div x-show="activePeriods.length === 0" class="mb-4 text-red-500 text-sm">
                    Belum ada pengaturan jam pelajaran untuk hari ini. <a href="{{ route('academic.time-settings.index') }}" class="underline">Atur Sekarang</a>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" @click="closeModal" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                    <button type="button" @click="deleteSchedule" x-show="mode === 'edit'" class="px-4 py-2 bg-red-500 text-white rounded">Hapus</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                </div>
            </form>
        </div>
    </x-modal>

    <!-- Alert Modal -->
    <x-modal name="alert-modal" focusable>
        <div class="bg-white p-6 rounded-lg w-full text-center">
            <div class="mb-4 flex justify-center">
                <template x-if="alertModal.type === 'success'">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                </template>
                <template x-if="alertModal.type === 'error'">
                    <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-red-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                </template>
            </div>
            
            <h2 class="text-xl font-bold mb-2" x-text="alertModal.title"></h2>
            <p class="mb-6 text-gray-600" x-text="alertModal.message"></p>
            
            <button type="button" @click="closeAlertModal" class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">OK</button>
        </div>
    </x-modal>

</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    function scheduleManager() {
        return {
            calendar: null,
            filters: {
                academic_year_id: '{{ \App\Models\AcademicYear::where("is_active", true)->value("id") }}',
                classroom_id: '',
                teacher_id: ''
            },
            mode: 'create',
            scheduleId: null,
            form: {
                course_id: '',
                day_of_week: 'monday',
                start_period: '',
                end_period: ''
            },
            activePeriods: [], // Period list for dropdown
            
            // Confirmation Modal State
            confirmModal: {
                title: '',
                message: '',
                action: null,
                params: null
            },
            
            // Alert Modal State
            alertModal: {
                title: '',
                message: '',
                type: 'success' // success, error
            },

            init() {
                var calendarEl = document.getElementById('schedule-calendar');
                this.calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    hiddenDays: [0], 
                    slotMinTime: '06:00:00',
                    slotMaxTime: '18:00:00',
                    allDaySlot: false,
                    headerToolbar: {
                        left: 'prev,next',
                        center: 'title',
                        right: 'timeGridWeek,listWeek'
                    },
                    events: {
                        url: '{{ route("academic.schedule.data") }}',
                        extraParams: () => {
                            return {
                                academic_year_id: this.filters.academic_year_id,
                                classroom_id: this.filters.classroom_id,
                                teacher_id: this.filters.teacher_id
                            };
                        }
                    },
                    editable: true,
                    eventDrop: (info) => {
                        this.openConfirmModal(
                            'Konfirmasi Pindah Jadwal', 
                            'Apakah Anda yakin ingin memindahkan jadwal ini?', 
                            'drop', 
                            info
                        );
                    },
                    eventClick: (info) => {
                        if (info.jsEvent.type === 'click') {
                            if (info.event.extendedProps.type === 'empty') {
                                // Clicked on an empty slot -> Open Create Modal Pre-filled
                                this.openModal('create', null, {
                                    day_of_week: info.event.extendedProps.day_of_week,
                                    period: info.event.extendedProps.period_number
                                });
                            } else if (info.event.extendedProps.type === 'class') {
                                // Clicked on existing class -> Open Edit Modal
                                this.openModal('edit', info.event);
                            }
                        }
                    }
                });
                this.calendar.render();
                
                // Fetch periods for initial state (monday)
                this.fetchPeriods();
            },

            refetch() {
                if (this.filters.teacher_id || (this.filters.academic_year_id && this.filters.classroom_id)) {
                    this.calendar.refetchEvents();
                } else {
                    this.calendar.removeAllEvents();
                }
            },

            async fetchPeriods() {
                try {
                    let res = await fetch(`{{ route('academic.time-settings.data') }}?day_of_week=${this.form.day_of_week}`);
                    this.activePeriods = await res.json();
                } catch (e) {
                    console.error('Failed to load periods', e);
                }
            },

            matchPeriod(timeStr) {
                 // Simple matcher: timeStr "07:00:00" or "07:00"
                 // periods have "07:00:00"
                 let time = timeStr.slice(0,5);
                 let found = this.activePeriods.find(p => p.start_time.slice(0,5) === time || p.end_time.slice(0,5) === time);
                 // If searching for start period, we match start_time. If end period, match end_time.
                 // But here we rely on the fact that schedules were created FROM periods.
                 return found ? found.period_number : '';
            },

            async openModal(mode, event = null, prefill = null) {
                this.mode = mode;
                if (mode === 'create') {
                    // Default values
                    this.form = {
                        course_id: '',
                        day_of_week: prefill ? prefill.day_of_week : 'monday',
                        start_period: prefill ? prefill.period : '',
                        end_period: prefill ? prefill.period : ''
                    };
                    this.scheduleId = null;
                    
                    // Fetch periods for the selected day (default Monday or prefilled day)
                    await this.fetchPeriods(); 
                } else {
                    this.scheduleId = event.id;
                    let day = this.getDayFromDate(event.start);
                    this.form.day_of_week = day;
                    
                    // Allow UI to update day select, then fetch periods
                    await this.fetchPeriods();
                    
                    // Prepare times for matching
                    let startTime = event.start.toTimeString().slice(0,5);
                    let endTime = event.end.toTimeString().slice(0,5);

                    // Find matching periods
                    let startP = this.activePeriods.find(p => p.start_time.slice(0,5) === startTime);
                    let endP = this.activePeriods.find(p => p.end_time.slice(0,5) === endTime);

                    this.form.course_id = event.extendedProps.course_id;
                    this.form.start_period = startP ? startP.period_number : '';
                    this.form.end_period = endP ? endP.period_number : '';
                }
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'schedule-modal' }));
            },

            closeModal() {
                 window.dispatchEvent(new CustomEvent('close-modal', { detail: 'schedule-modal' }));
            },

            // Confirmation Modal Functions
            openConfirmModal(title, message, action, params = null) {
                this.confirmModal.title = title;
                this.confirmModal.message = message;
                this.confirmModal.action = action;
                this.confirmModal.params = params;
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirmation-modal' }));
            },

            closeConfirmModal() {
                window.dispatchEvent(new CustomEvent('close-modal', { detail: 'confirmation-modal' }));
                
                if (this.confirmModal.action === 'drop' && this.confirmModal.params) {
                    this.confirmModal.params.revert();
                }
                
                this.resetConfirmState();
            },

            resetConfirmState() {
                this.confirmModal = { title: '', message: '', action: null, params: null };
            },

            async confirmAction() {
                const action = this.confirmModal.action;
                const params = this.confirmModal.params;

                window.dispatchEvent(new CustomEvent('close-modal', { detail: 'confirmation-modal' }));

                if (action === 'drop') {
                    await this.processDrop(params);
                } else if (action === 'delete') {
                    await this.processDelete();
                }
                
                this.resetConfirmState();
            },
            
            // Alert Modal Functions
            openAlertModal(title, message, type = 'success') {
                this.alertModal.title = title;
                this.alertModal.message = message;
                this.alertModal.type = type;
                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'alert-modal' }));
            },

            closeAlertModal() {
                window.dispatchEvent(new CustomEvent('close-modal', { detail: 'alert-modal' }));
            },

            getDayFromDate(date) {
                const days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                return days[date.getDay()];
            },

            handleDrop(info) {
                 // Moved to openConfirmModal call in init()
            },

            async processDrop(info) {
                const day = this.getDayFromDate(info.event.start);
                const start = info.event.start.toTimeString().slice(0,5);
                const end = info.event.end.toTimeString().slice(0,5);

                try {
                    let response = await fetch('/academic/schedules/' + info.event.id, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            course_id: info.event.extendedProps.course_id,
                            day_of_week: day,
                            start_time: start,
                            end_time: end
                        })
                    });

                    if (!response.ok) {
                        let error = await response.json();
                        this.openAlertModal('Gagal', 'Gagal memindahkan: ' + error.message, 'error');
                        info.revert();
                    } else {
                        this.openAlertModal('Berhasil', 'Jadwal berhasil dipindahkan', 'success');
                    }
                } catch (e) {
                    this.openAlertModal('Error', 'Terjadi kesalahan sistem', 'error');
                    info.revert();
                }
            },

            async submit() {
                let url = this.mode === 'create' ? '/academic/schedules' : '/academic/schedules/' + this.scheduleId;
                let method = this.mode === 'create' ? 'POST' : 'PUT';

                try {
                    let response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(this.form)
                    });

                    if (response.ok) {
                        this.closeModal();
                        this.refetch();
                        this.openAlertModal('Berhasil', 'Jadwal berhasil disimpan', 'success');
                    } else {
                        let error = await response.json();
                        this.openAlertModal('Gagal', error.message || 'Konflik Jadwal', 'error');
                    }
                } catch (e) {
                    console.error(e);
                    this.openAlertModal('Error', 'Terjadi kesalahan sistem', 'error');
                }
            },

            async deleteSchedule() {
                // Close the Edit Modal first - logic handled in click handler, 
                // but deleteSchedule is called by confirmAction via processDelete
                // so we don't need to close modals here again, confirmAction did it.
            },
            
            // This is called by confirmAction
            async processDelete() {
                try {
                    let response = await fetch('/academic/schedules/' + this.scheduleId, {
                         method: 'DELETE',
                         headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                    });

                    if (response.ok) {
                        this.refetch();
                        // Triggered from Edit Modal -> Delete Button -> Confirm Modal -> Here.
                        // Edit modal was closed in deleteSchedule() (the trigger function).
                        this.openAlertModal('Berhasil', 'Jadwal berhasil dihapus', 'success');
                    }
                } catch (e) {
                     this.openAlertModal('Error', 'Gagal menghapus jadwal', 'error');
                }
            }
        }
    }
</script>
@endsection
