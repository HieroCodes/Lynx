<template>
    <div class='demo-app'>
        <div class='demo-app-sidebar'>
            <div class='demo-app-sidebar-section'>
                <h2><b style="font-family: 'Gilroy ExtraBold';">Instructions</b></h2>
                <ul>
                    <li>Select dates and you will be prompted to create a new event</li>
                    <li>Drag, drop, and resize events</li>
                    <li>Click an event to delete it</li>
                </ul>
            </div>
            <div class='demo-app-sidebar-section'>
                <label>
                    <input
                        type='checkbox'
                        :checked='calendarOptions.weekends'
                        @change='handleWeekendsToggle'
                    />
                    toggle weekends
                </label>
            </div>
            <div class='demo-app-sidebar-section'>
                <h2>Tous les Evenements ({{ currentEvents.length }})</h2>
                <ul>
                    <li v-for='event in currentEvents' :key='event.id'>
                        <b>{{ event.startStr }}</b>
                        <i>{{ event.title }}</i>
                    </li>
                </ul>
            </div>
        </div>

        <div class='demo-app-main'>
            <FullCalendar
                class='demo-app-calendar'
                :options='calendarOptions'>

                <template v-slot:eventContent='arg'>
                    <b>{{ arg.timeText }}</b>
                    <i>{{ arg.event.title }}</i>
                    <br>
                    <button @click="editEvent(arg.event)">Edit</button>
                    <button @click="deleteEvent(arg.event)">Delete</button>
                </template>

            </FullCalendar>
        </div>
    </div>
</template>

<script>
import { defineComponent } from 'vue'
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import { INITIAL_EVENTS, createEventId } from '../events-utils.js'


export default defineComponent({
    components: {
        FullCalendar,
    },
    data() {
        return {
            calendarOptions: {
                plugins: [
                    dayGridPlugin,
                    timeGridPlugin,
                    interactionPlugin // needed for dateClick
                ],
                local: 'fr-FR',
                headerToolbar: {
                    left: 'prev, today ,next',
                    center: 'title',
                    right: 'dayGridMonth, timeGridWeek, timeGridDay',

                },
                buttonText : {
                    today: 'Aujourd\'hui',
                        month: 'Mois',
                        week : 'Semaine',
                        day : 'Jour',
                        list : 'Liste'

                },

        initialView: 'dayGridMonth',
                initialEvents: INITIAL_EVENTS, // alternatively, use the `events` setting to fetch from a feed
                editable: true,
                selectable: true,
                selectMirror: true,
                dayMaxEvents: true,
                weekends: true,
                select: this.handleDateSelect,
                eventClick: this.handleEventClick,
                eventsSet: this.handleEvents
                /* you can update a remote database when these fire:
                eventAdd:
                eventChange:
                eventRemove:
                */
            },
            currentEvents: [],
        }
    },
    methods: {
        handleWeekendsToggle() {
            this.calendarOptions.weekends = !this.calendarOptions.weekends // update a property
        },
        handleDateSelect(selectInfo) {
            let title = prompt('Please enter a new title for your event')
            let calendarApi = selectInfo.view.calendar

            calendarApi.unselect() // clear date selection

            if (title) {
                calendarApi.addEvent({
                    id: createEventId(),
                    title,
                    start: selectInfo.startStr,
                    end: selectInfo.endStr,
                    allDay: selectInfo.allDay
                })
            }
        },
        // Méthode pour éditer un événement
        editEvent(event) {
            const newTitle = prompt('Modifiez le titre de l\'événement', event.title)
            if (newTitle) {
                event.title = newTitle
            }
        },

        // Méthode pour supprimer un événement
        /*deleteEvent(event) {
            if (confirm(`Êtes-vous sûr de vouloir supprimer l'événement "${event.title}"`)) {
                const index = this.calendarOptions.events.indexOf(event)
                if (index !== -1) {
                    this.calendarOptions.events.splice(index, 1)
                }
            }
        }, */

        handleEventClick(clickInfo) {
            if (confirm(`Are you sure you want to delete the event '${clickInfo.event.title}'`)) {
                clickInfo.event.remove()
            }
        },
        handleEvents(events) {
            this.currentEvents = events
        },
    },
})

</script>

<style lang='css'>

.event-modal {
    position: absolute;
    background-color: #fff;
    border: 1px solid #ddd;
    padding: 10px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    z-index: 1000; /* Ajustez la valeur du z-index selon vos besoins */
}


h2 {
    margin: 0;
    font-size: 16px;
}

ul {
    margin: 0;
    padding: 0 0 0 1.5em;
}

li {
    margin: 1.5em 0;
    padding: 0;
}

b { /* used for event dates/times */
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

.fc { /* the calendar root */
    max-width: 1100px;
    margin: 0 auto;
}

</style>
