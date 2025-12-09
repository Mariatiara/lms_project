@extends('layouts.dashboard')
@section('title', 'Pengaturan Jam Pelajaran')
@section('header', 'Pengaturan Jam Pelajaran')

@section('content')
<div class="container mx-auto" x-data="timeSettings()">
    <div class="bg-white p-6 rounded-xl shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">Konfigurasi Jam Pelajaran</h2>
            <div class="space-x-2">
                <button @click="generateStandard" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Generate Standar (07:00, 45m)
                </button>
                <button @click="save" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </div>
        </div>

        <div class="mb-4">
            <label class="font-bold mr-2">Pilih Hari:</label>
            <select x-model="activeDay" class="border rounded px-3 py-1">
                <option value="monday">Senin</option>
                <option value="tuesday">Selasa</option>
                <option value="wednesday">Rabu</option>
                <option value="thursday">Kamis</option>
                <option value="friday">Jumat</option>
                <option value="saturday">Sabtu</option>
                <option value="sunday">Minggu</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b">
                        <th class="p-3">Label</th>
                        <th class="p-3">Jam Ke-</th>
                        <th class="p-3">Mulai</th>
                        <th class="p-3">Selesai</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(setting, index) in settings[activeDay]" :key="index">
                        <tr class="border-b">
                            <td class="p-3">
                                <input type="text" x-model="setting.label" class="border rounded px-2 py-1 w-full" placeholder="Contoh: Jam 1">
                            </td>
                            <td class="p-3">
                                <input type="number" x-model="setting.period_number" class="border rounded px-2 py-1 w-20" placeholder="Null for break">
                                <p class="text-xs text-gray-500">Kosongkan jika istirahat</p>
                            </td>
                            <td class="p-3">
                                <input type="time" x-model="setting.start_time" class="border rounded px-2 py-1">
                            </td>
                            <td class="p-3">
                                <input type="time" x-model="setting.end_time" class="border rounded px-2 py-1">
                            </td>
                            <td class="p-3">
                                <button @click="removeSlot(index)" class="text-red-600 hover:text-red-800">Hapus</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            
            <button @click="addSlot" class="mt-4 text-blue-600 hover:text-blue-800 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Slot
            </button>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <x-modal name="confirm-modal" focusable>
        <div class="bg-white p-6 rounded-lg w-full">
            <h2 class="text-xl font-bold mb-4" x-text="confirmModal.title"></h2>
            <p class="mb-6 text-gray-600" x-text="confirmModal.message"></p>
            <div class="flex justify-end gap-2">
                <button type="button" @click="closeConfirmModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                <button type="button" @click="confirmAction" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Ya, Lanjutkan</button>
            </div>
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


<script>
function timeSettings() {
    return {
        activeDay: 'monday',
        settings: {
            monday: [],
            tuesday: [],
            wednesday: [],
            thursday: [],
            friday: [],
            saturday: [],
            sunday: []
        },
        
        confirmModal: {
            title: '',
            message: '',
            action: null
        },
        
        alertModal: {
            title: '',
            message: '',
            type: 'success'
        },

        init() {
            // Load existing settings if any
            let existing = @json($settings);
            
            // Populate settings structure
            ['monday','tuesday','wednesday','thursday','friday','saturday','sunday'].forEach(day => {
                let daySettings = existing[day] || [];
                // Fix time format from DB (e.g. 07:00:00 -> 07:00) so input type="time" works cleanly and validation passes
                this.settings[day] = daySettings.map(s => ({
                    ...s,
                    start_time: s.start_time ? s.start_time.substring(0, 5) : '',
                    end_time: s.end_time ? s.end_time.substring(0, 5) : ''
                }));
            });
        },

        addSlot() {
            this.settings[this.activeDay].push({
                day_of_week: this.activeDay,
                label: 'Jam Baru',
                period_number: null,
                start_time: '00:00',
                end_time: '00:00'
            });
        },

        removeSlot(index) {
            this.settings[this.activeDay].splice(index, 1);
        },

        generateStandard() {
            this.openConfirmModal(
                'Konfirmasi Generate', 
                'Ini akan menimpa pengaturan hari ini dengan jadwal standar (07:00 - selesai, durasi 45 menit). Lanjutkan?', 
                'generate'
            );
        },

        processGenerateStandard() {
            let slots = [];
            let startTime = new Date();
            startTime.setHours(7, 0, 0, 0); // Start 07:00

            // 8 Jam Pelajaran x 45 menit + 2 Istirahat
            
            let currentTime = new Date(startTime);

            for (let i = 1; i <= 8; i++) {
                // Break after 4th period
                if (i === 5) {
                    let breakStart = new Date(currentTime);
                    currentTime.setMinutes(currentTime.getMinutes() + 15); // 15 min break
                    let breakEnd = new Date(currentTime);
                    
                    slots.push({
                        day_of_week: this.activeDay,
                        label: 'Istirahat',
                        period_number: null,
                        start_time: breakStart.toTimeString().slice(0,5),
                        end_time: breakEnd.toTimeString().slice(0,5)
                    });
                }

                let start = new Date(currentTime);
                currentTime.setMinutes(currentTime.getMinutes() + 45);
                let end = new Date(currentTime);

                slots.push({
                    day_of_week: this.activeDay,
                    label: 'Jam Ke-' + i,
                    period_number: i,
                    start_time: start.toTimeString().slice(0,5),
                    end_time: end.toTimeString().slice(0,5)
                });
            }

            this.settings[this.activeDay] = slots;
            this.openAlertModal('Berhasil', 'Jadwal standar berhasil dibuat. Jangan lupa Simpan Perubahan.', 'success');
        },

        async save() {
            // Flatten settings object to array and sanitize data
            let allSettings = [];
            for (const day in this.settings) {
                // Ensure time is HH:mm (strip seconds if present)
                let daySlots = this.settings[day].map(s => ({
                    ...s,
                    start_time: s.start_time.substring(0, 5),
                    end_time: s.end_time.substring(0, 5)
                }));
                allSettings.push(...daySlots);
            }

            try {
                let response = await fetch('{{ route("academic.time-settings.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ settings: allSettings })
                });

                if (response.ok) {
                    let result = await response.json();
                    this.openAlertModal('Berhasil', 'Pengaturan berhasil disimpan! Total ' + result.count + ' slot disimpan.', 'success');
                } else {
                    let err = await response.json();
                    this.openAlertModal('Gagal', 'Gagal menyimpan: ' + (err.message || 'Error validasi'), 'error');
                }
            } catch (e) {
                console.error(e);
                this.openAlertModal('Error', 'Terjadi kesalahan sistem', 'error');
            }
        },

        // Helper Methods for Modals
        openConfirmModal(title, message, action) {
            this.confirmModal.title = title;
            this.confirmModal.message = message;
            this.confirmModal.action = action;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-modal' }));
        },

        closeConfirmModal() {
            window.dispatchEvent(new CustomEvent('close-modal', { detail: 'confirm-modal' }));
        },

        confirmAction() {
            this.closeConfirmModal();
            if (this.confirmModal.action === 'generate') {
                this.processGenerateStandard();
            }
        },

        openAlertModal(title, message, type = 'success') {
            this.alertModal.title = title;
            this.alertModal.message = message;
            this.alertModal.type = type;
            window.dispatchEvent(new CustomEvent('open-modal', { detail: 'alert-modal' }));
        },

        closeAlertModal() {
            window.dispatchEvent(new CustomEvent('close-modal', { detail: 'alert-modal' }));
        }
    }
}
</script>
@endsection
