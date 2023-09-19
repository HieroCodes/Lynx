

<template>
    <div class="flex-container">
        <p class="titre">Trier les évènements:</p>
        <DateRange @dateRangeSelected="filterEventsByDate" />
    </div>
    <div>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="event in events" :key="event.id">
                <td><strong>{{ event.id }}</strong></td>
                <td>{{ event.title }}</td>
                <td>{{ event.description }}</td>
                <td>{{ event.date }}</td>
            </tr>
            </tbody>
        </table>
        <!-- ... Pagination ... -->
        <div class="pagination-container">
            <ul v-if="totalItems > itemsPerPage" class="pagination">
                <li v-for="n in Math.ceil(totalItems / itemsPerPage)" :key="n"
                    :class="{ 'active': n === currentPage }">
                    <button @click="changePage(n)">{{ n }}</button>
                </li>
            </ul>
        </div>
    </div>
</template>


<script>

import DateRangePicker from "@/Components/DateRangePicker.vue";
import DateRange from "@/Components/DateRange.vue";
import axios from "axios";
export default {
    components:{
        DateRangePicker,
        DateRange
    },
    mounted() {
        this.fetchAllEvents();
    },

    data(){
        return{
            events: [],
            currentPage: 1,
            itemsPerPage: 10,
            totalItems: 0,
        }
    },
    methods:{
        fetchAllEvents() {
            axios.get('/events', { params: { page: this.currentPage } })
                .then(response => {
                    console.log(response.data);
                    this.events = response.data.events;
                    this.totalItems = response.data.total;
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des événements:', error);
                });
        },
        filterEventsByDate([startDate, endDate]) {

            console.log("Plage de dates sélectionnée:", startDate, endDate);
            axios.get('/events', {
                params: {
                    start_date: startDate,
                    end_date: endDate,
                    page: this.currentPage,
                }
            })
                .then(response => {
                    console.log("Réponse du backend:", response.data.events);
                    this.events = response.data.events;
                    this.totalItems = response.data.total;
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des événements filtrés:', error);
                });
        },
        changePage(pageNumber) {
            this.currentPage = pageNumber;
            this.fetchAllEvents();
        }
    }
}
</script>


<style scoped>
.flex-container {
    display: flex;
    justify-content: space-between;
    align-items: center;  /* Centre le contenu verticalement */
    padding: 7px;        /* Ajoute de l'espace autour du contenu */
    margin: 3px;
}

.titre{
    font-family: "Gilroy ExtraBold",serif;
    font-size: 20px;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px 12px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

tr:hover {
    background-color: #f5f5f5;
}
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination {
    list-style-type: none;
    padding: 0;
    display: flex;
}

.pagination li {
    margin: 0 5px;
}

.pagination li button {
    padding: 5px 10px;
    border: none;
    background-color: #eee;
    cursor: pointer;
    transition: background-color 0.3s;
}

.pagination li button:hover {
    background-color: #ddd;
}

.pagination li.active button {
    background-color: #666;
    color: #fff;
}

</style>
