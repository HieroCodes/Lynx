<template>
    <div v-if="show" @click.self="closeModal" class="modal-background">
        <div class="modal-content">
            <h1><strong style="">Détails de l'événement</strong></h1>
            <br>
            <h2><strong>Titre : </strong>{{ event.title }}</h2>
            <p><strong>Description : </strong>{{ event.description }}</p>
            <pre>{{ event }}</pre>
            <br>
            <div class="btn-container">
                <button @click="openEditModal"><strong>Modifier</strong></button>
                <button @click="deleteEvent" style="color: #a71d5d; font-weight: bold; ">Supprimer</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        show: {
            type: Boolean,
            default: false
        },
        event: {
            type: Object,
            default: () => ({})
        }
    },
    methods: {
        closeModal() {
            this.$emit('close');
        },
        openEditModal() {
            this.$emit('edit', this.event);
        },
        deleteEvent() {
            console.log(this.event);
            axios.delete(`/events/${this.event.id}`)
                .then(() => {
                    this.$emit('eventDeleted', this.event.id);
                    // Informe le composant parent de la suppression en utilisant this.event.id
                    this.closeModal();
                    // Fermez la modale après la suppression
                    // Rafraîchir la page
                    window.location.reload();
                })
                .catch(error => {
                    console.error("Erreur lors de la suppression:", error);
                });
        },

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
