<template>
    <div>

        <div v-if="showPicker" class="calendar-picker"><br>
         <strong>Du</strong>   <input type="date" v-model="startDate">
            <strong> au </strong><input type="date" v-model="endDate">&ensp;
            <button @click="applyRange" class="apply-button">Appliquer</button>
        </div>
        <br>
    </div>
</template>

<script>
export default {
    data() {
        return {
            startDate: '',
            endDate: '',
            showPicker: true
        };
    },
    computed: {
        formattedDateRange() {
            return `${this.startDate} - ${this.endDate}`;
        }
    },
    methods: {
        applyRange() {
            this.showPicker = true;
            this.$emit('dateRangeSelected', [this.startDate, this.endDate]);
        }
    }
}
</script>

<style scoped>
.calendar-picker {
    /* Styles pour le calendrier ici */
}
.apply-button {
    background-color: violet;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

.apply-button:hover {
    background-color: darkviolet;  /* Change la couleur de fond en violet foncé lorsque le bouton est survolé */
}
</style>
