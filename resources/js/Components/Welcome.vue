<template>
    <div class="demo-app">
        <div class="demo-app-sidebar">
            <div class="demo-app-sidebar-section">
                <h2 class="header"><b>Instructions</b></h2>
                <ul>
                    <li><strong> + </strong>Selectionner une date pour créer un évènement</li>
                    <li><strong> + </strong>Cliquez sur un évèement pour le modifier ou le supprimer</li>
                    <li><strong> + </strong>Cliquez, puis clisser un évènement d'une date a une autre du Calendrier</li>
                    <li><strong> + </strong>Filtrer les évènements par date dans le menu "Filtres"</li>
                </ul>
            </div>
            <div class="demo-app-sidebar-section">
                <h2 class="header">Liste des Evenements ({{ currentEvents.length }})</h2>
                <ul>
                    <li v-for="event in displayedEvents" :key="event.id">
                        <b>{{ event.startStr }}</b>
                        <i>{{ event.title }}</i>
                    </li>
                </ul>
                <button v-if="!showAllEvents && currentEvents.length > 9" @click="showAllEvents = true">Voir plus</button>
                <button v-else-if="showAllEvents" @click="showAllEvents = false">Voir moins</button>
            </div>

        </div>
        <div class="demo-app-main">

            <FullCalendar
                class="demo-app-calendar"
                :options="calendarOptions"
                :events="events"
                ref="calendarRef"
            />
        </div>
        <EventModal :show="showModal" :date="selectedDate" @close="showModal=false" @event-added="addEvent" />
        <EventModalDetails
            :show="showEventDetailsModal"
            :event="selectedEvent"
            @close="closeEventDetailsModal"
            @edit="editEvent"
            @delete="deleteEvent"
        />
        <EventModalUpdate
            :key="selectedEvent ? selectedEvent.id : null"
            :eventToEdit="selectedEvent"
            :showEditModal="showEditModal"
            @close-edit-modal="showEditModal = false"
            @event-updated="handleEventUpdated"
            @eventDeleted="handleEventDeleted"
        />

    </div>
</template>

<script>
import { defineComponent, ref } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import frLocale from '@fullcalendar/core/locales/fr.js'

import axios from 'axios';
import { INITIAL_EVENTS, createEventId } from '../events-utils.js'
import EventModal from "@/Components/EventModal.vue";
import EventModalDetails from "@/Components/EventModalDetails.vue";
import EventModalUpdate from "@/Components/EventModalUpdate.vue";
import DateRangePicker from './DateRangePicker.vue';


export default defineComponent({
    components: {
        DateRangePicker,
        EventModalUpdate,
        EventModalDetails,
        EventModal,
        FullCalendar
    },
    props: {
        initialEvents: Array
    },
    computed: {
        displayedEvents() {
            if (this.showAllEvents) {
                return this.currentEvents;
            } else {
                return this.currentEvents.slice(0, 7);
            }
        }
    },

    mounted() {
        if (!this.events.length) { // Si "events" est vide, récupérez les données
            this.fetchEvents();
        }
    },
    methods: {
        fetchEvents() {
            axios.get('/events').then(response => {
                this.events = this.eventsToFullCalendar(response.data.events);

                // Forcez FullCalendar à re-render
                const calendarApi = this.$refs.calendarRef.getApi();
                calendarApi.removeAllEvents();
                this.events.forEach(event => {
                    calendarApi.addEvent(event);
                });
            });
        },

        createEvent(eventData) {
            axios.post('/events', eventData)
                .then(response => {
                    // Rafraîchir la page actuelle
                    window.location.reload();
                });
        },


        handleEvents(events) {
            this.currentEvents = events;
        },
        handleDateSelect(selectInfo) {
            this.showModal = true;
            this.selectedDate = selectInfo.startStr;
        },
        addEvent(event) {
            this.calendarOptions.initialEvents.push(event);
        },
        eventsToFullCalendar(events) {
            if (!events) return []; // Ajouté pour gérer les valeurs null ou undefined
            return events && Array.isArray(events) ? events.map(event => ({id: event.id, title: event.title, description: event.description, start: event.date })) : [];
        },
        //Event Details
        handleEventClick(info) {
            this.selectedEvent = {
                id: info.event.id,
                title: info.event.title,
                description: info.event.extendedProps.description
            };
            this.showEventDetailsModal = true;
        },
        closeEventDetailsModal() {
            this.showEventDetailsModal = false;
        },
        editEvent(event) {
            console.log("editEvent is called with event:", event);
            this.selectedEvent = event; // Assignez l'événement sélectionné
            this.showEditModal = true; // Ouvrez EventModalUpdate
            this.showEventDetailsModal = false; // Fermez EventModalDetails
        },

        deleteEvent() {
            // La logique pour supprimer l'événement
            this.closeEventDetailsModal();
        },
        //
        handleEventUpdated(updatedEvent) {
            // Trouvez l'index de l'événement mis à jour dans la liste des événements
            const index = this.events.findIndex(event => event.id === updatedEvent.id);

            // Mettez à jour l'événement dans la liste
            if (index !== -1) {
                this.events[index] = updatedEvent;
            }

            // Mettez également à jour l'affichage de FullCalendar
            const calendarApi = this.$refs.calendarRef.getApi();
            const event = calendarApi.getEventById(updatedEvent.id);
            if (event) {
                event.setProp('title', updatedEvent.title);
                event.setExtendedProp('description', updatedEvent.description);
            }
        },
        //
        handleEventDeleted(eventId) {
            // Retirez l'événement de la liste des événements (ou de l'état de votre application)
            this.events = this.events.filter(event => event.id !== eventId);
        },
        //
        filterEventsByDate([startDate, endDate]) {
            // Utilisez axios ou votre moyen préféré pour récupérer les événements
            // par exemple:
            axios.get('/events', {
                params: {
                    start_date: startDate,
                    end_date: endDate
                }
            })
                .then(response => {
                    this.events = response.data;
                });
        }


        },
    data() {
        return {
            events: [],
            locales: [ frLocale ],
            locale: 'fr',
            //
            showEditModal: false,
            //
            showEventDetailsModal: false,
            selectedEvent: null,
            //
            showModal: false,
            selectedDate: null,
            //
            showAllEvents: false,
            calendarOptions: {

                initialEvents: this.events,
                events: this.events,
                plugins: [ dayGridPlugin, timeGridPlugin, interactionPlugin ],
                headerToolbar: {
                    left: 'prev, today ,next',
                    center: 'title',
                    right: 'dayGridMonth, timeGridWeek, timeGridDay'
                },
                buttonText: {
                    today: 'Aujourd\'hui',
                    month: 'Mois',
                    week: 'Semaine',
                    day: 'Jour',
                    list: 'Liste'
                },
                initialView: 'dayGridMonth',
                //initialEvents: INITIAL_EVENTS,
                editable: true,
                selectable: true,
                selectMirror: true,
                dayMaxEvents: true,
                weekends: true,
                select: this.handleDateSelect,
                eventClick: this.handleEventClick,
                eventsSet: this.handleEvents
            },
            currentEvents: []
        }
    }
})
</script>

<style lang="css">
.event-modal {
    position: absolute;
    background-color: #fff;
    border: 1px solid #ddd;
    padding: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    z-index: 1000;
}

.header {
    margin: 0;
    font-size: 16px;
}

ul {
    margin: 0;
    padding: 0 0 0 1.5em;
}

li {
    margin: 1.5em 0;
}

b {
    margin-right: 3px;
}

.demo-app {
    display: flex;
    min-height: 100%;
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 14px;
}

.demo-app-sidebar {
    width: 300px;
    line-height: 1.5;
    background: #eaf9ff;
    border-right: 1px solid #d3e2e8;
}

.demo-app-sidebar-section {
    padding: 2em;
}

.demo-app-main {
    flex-grow: 1;
    padding: 3em;
}

.fc {
    max-width: 1100px;
    margin: 0 auto;
}
</style>
