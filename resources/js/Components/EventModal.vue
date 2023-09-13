<template>
    <div v-if="show" class="modal">
        <h2>Ajouter un événement</h2>
        <input v-model="eventTitle" placeholder="Titre" />
        <br>
        <br>
        <textarea v-model="eventDescription" placeholder="Description"></textarea>
        <br>
        <br>
        <div class="btn-container">
            <button @click="saveEvent"><strong>Ajouter</strong></button>
            <button @click="closeModal" style="color: #a71d5d; font-weight: 600; ">Fermer</button>
        </div>

    </div>
</template>

<script>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

export default {
    props: ['show', 'date'],
    setup(props, { emit }) {
        const eventTitle = ref('');
        const eventDescription = ref(''); // Ajoutez cette ligne

        function closeModal() {
            emit('close');
        }

        function saveEvent() {
            Inertia.post('/events', { title: eventTitle.value, description: eventDescription.value, date: props.date });
            closeModal();
        }


        return {
            eventTitle,
            eventDescription,
            saveEvent,
            closeModal
        };
    }
}
</script>

<style>
/* Ajoutez vos styles pour le modal ici */
.modal {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: white;
    padding: 20px;
    border: 1px solid #ccc;
    box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
    z-index: 1000;
}
.btn-container {
    display: flex;
    justify-content: space-between;
}

</style>
