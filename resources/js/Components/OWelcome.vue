<template>
    <div>
        <FullCalendar
            :events="events"
            @dateClick="openModal"
            :headerToolbar="{
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
      }"
            plugins="[dayGridPlugin]"
        />

    </div>
</template>

<script>
import { ref } from 'vue';
import FullCalendar  from '@fullcalendar/vue3'

export default {
    components: {
        FullCalendar
    },
    setup() {
        const events = ref([]);  // Load this from your API
        const showModal = ref(false);
        const selectedDate = ref(null);

        function openModal(info) {
            selectedDate.value = info.dateStr;
            showModal.value = true;
        }

        return {
            events,
            showModal,
            selectedDate,
            openModal
        };
    }
}
</script>
