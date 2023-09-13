<template>
    <div v-if="showEditModal" @click.self="closeModal" class="modal-background">
        <div class="modal-content">
        <h1><strong>Modifier l'événement</strong></h1>
        <input v-model="editedEvent.title" placeholder="Titre" />
            <br>
            <br>
        <textarea v-model="editedEvent.description" placeholder="Description"></textarea>
            <br>
            <br>
            <div class="btn-container">
            <button @click="updateEvent">Enregistrer</button>
            <button @click="closeModal" style="color: #a71d5d; font-weight: 600; ">Fermer</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {

    props: ['eventToEdit', 'showEditModal'],
    data() {
        return {
            editedEvent: { ...this.eventToEdit }
        };
    },
    watch: {
        eventToEdit: {
            deep: true,
            handler(newValue) {
                this.editedEvent = { ...newValue };
            }
        }
    },
    methods: {

        updateEvent() {
            console.log('Data sent:', {
                title: this.editedEvent.title,
                description: this.editedEvent.description,
                date: this.editedEvent.date
            });

            axios.post(`/events/${this.eventToEdit.id}`, {
                title: this.editedEvent.title,
                description: this.editedEvent.description,
                //date: this.editedEvent.date
            })
                .then(response => {
                    console.log(response.data.message);
                    window.location.reload();
                })
                .catch(error => {
                    console.error("Erreur lors de la mise à jour:", error);
                    if (error.response && error.response.data) {
                        console.log("Détails de l'erreur:", error.response.data);
                    }
                });

        },

        closeModal() {
            this.$emit('close-edit-modal');
        }
    }

}
</script>
<style scoped>
.modal-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0,0,0,0.6);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background-color: #fff;
    padding: 2rem;
    border-radius: 8px;
    position: relative;
}
.btn-container {
    display: flex;
    justify-content: space-between;
}
</style>

