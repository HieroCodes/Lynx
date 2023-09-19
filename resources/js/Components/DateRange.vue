<template>
    <div>
        <input type="text" placeholder="-selectionnez une plage" v-model="displayedDateRange" @click="showCalendar = !showCalendar" readonly>
        <div v-if="showCalendar" class="calendar">
            <div class="calendar-header">
                <button @click="prevMonth">«</button>
                <span>
                    <select v-model="currentMonth" @change="updateMonth">
                       <option v-for="(monthName, index) in months" :key="index" :value="index">{{ monthName }}</option>
                    </select>
                   </span>
                <select v-model="currentYear" @change="updateYear">
                    <option v-for="year in availableYears" :key="year">{{ year }}</option>
                </select>
                <button @click="nextMonth">»</button>
            </div>

            <br/>

            <div class="calendar-weekdays">
                <div v-for="day in weekdays">{{ day }}</div>
            </div>

            <div class="calendar-days">
            <div v-for="day in daysInMonth"
                 @click="selectDate(day)"
                 :class="{ 'selected': isDaySelected(day),'today': isToday(day) }
                 ">
                {{ day }}
            </div>
            </div>
        </div>
        &ensp;
        <button @click="applyRange" class="apply-button">Filtrer</button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            start: '',
            end: '',
            displayedDateRange: '',
            showCalendar: false,
            currentMonth: new Date().getMonth(),
            currentYear: new Date().getFullYear(),
            weekdays: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            availableYears: Array.from({length: 20}, (_, i) => new Date().getFullYear() - i)
        };
    },
    mounted() {
        document.body.addEventListener('click', this.handleClickOutside);
    },
    beforeDestroy() {
        document.body.removeEventListener('click', this.handleClickOutside);
    },
    computed: {
        daysInMonth() {
            return new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
        },
        currentMonthName() {
            return new Date(this.currentYear, this.currentMonth).toLocaleString('fr-FR', { month: 'long' });
        }
    },
    methods: {
        selectDate(day) {
            const date = `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

            if (!this.start || (this.start && this.end)) {
                this.start = date;
                this.end = '';
            } else if (!this.end && date > this.start) {
                this.end = date;
                this.displayedDateRange = `${this.formatDate(this.start)} - ${this.formatDate(this.end)}`;
            }
            if (this.end) {
                //this.showCalendar = false;
            }
        },
        isDaySelected(day) {
            const date = `${this.currentYear}-${String(this.currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            return date === this.start || date === this.end;
        },
        isToday(day) {
            const today = new Date();
            return day === today.getDate() && this.currentMonth === today.getMonth() && this.currentYear === today.getFullYear();
        },
        formatDate(date) {
            let d = new Date(date);
            return `${("0" + (d.getDate())).slice(-2)}/${("0" + (d.getMonth() + 1)).slice(-2)}/${d.getFullYear()}`;
        },
        applyRange() {
            if (this.start && this.end) {
                this.$emit('dateRangeSelected', [this.start, this.end]);
                this.showCalendar = false;
            }

        },
        prevMonth() {
            if (this.currentMonth === 0) {
                this.currentMonth = 11;
                this.currentYear--;
            } else {
                this.currentMonth--;
            }
        },
        nextMonth() {
            if (this.currentMonth === 11) {
                this.currentMonth = 0;
                this.currentYear++;
            } else {
                this.currentMonth++;
            }
        },
        updateYear() {
            this.currentMonth = 0;
        },
        handleClickOutside(e) {
            if (!this.$el.contains(e.target)) {
                this.showCalendar = false;
            }
        }
    }
};
</script>

<style scoped>
.calendar {
    position: absolute;
    background: white;
    border: 1px solid #ccc;
    padding: 10px;
    z-index: 10;
    width: 300px;
    align-content: center;
    align-items: center;
}

.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
}

.calendar-days {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 10px;
}
.calendar-weekdays {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 10px;
    margin-bottom: 10px;
}

.today {
    background-color: green;
    color: white;
}

.selected {
    background: darkviolet;
    color: white;
    font-weight: 800;
}


.apply-button {
    background-color: darkviolet;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

.apply-button:hover {
    background-color: violet;
}
</style>
