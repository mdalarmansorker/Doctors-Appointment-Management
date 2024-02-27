<template>
    <div v-if="loaded">
        <a-calendar v-model:value="value">
            <template #dateCellRender="{ current: value }">
                <ul class="events">
                    <li v-for="item in getEventsForDate(value)" :key="item.id">
                        <a-badge :status="'warning'" :text="item['name']" />
                    </li>
                </ul>
            </template>
            <template #monthCellRender="{ current: value }">
                <div v-if="getMonthData(value)" class="notes-month">
                    <section>{{ getMonthData(value) }}</section>
                    <span>Backlog number</span>
                </div>
            </template>
        </a-calendar>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
import { Moment } from "moment";
import axios from "axios";

export default defineComponent({
    setup() {
        const value = ref<Moment>();
        const doctorID = localStorage.getItem("UserID");
        const loaded = ref(false);
        
        let events = [];
        let appointments = {};
        const fetchAppointments = async () => {
            const response = await axios.get(
                `/api/doctor-pending-appointments/${doctorID}`
            );
            events = response.data;
            // console.log(doctorID);
            loaded.value = true;
            console.log(events);
            for (let item of events)
            {
              if(!appointments[item.date])
              {
                appointments[item.date] = [];
              }
              appointments[item.date].push(item);
              console.log('app', appointments);
              
            }
        };

        onMounted(fetchAppointments);

        // const eventsByDate = events.reduce((acc, event) => {
        //     const date = event.date;
        //     console.log(event);
        //     if (!acc[date]) {
        //         acc[date] = [];
        //     }
        //     acc[date].push(event);
        //     return acc;
        // }, {});

        const getEventsForDate = (value: Moment) => {
            const currentDate = value.format("YYYY-MM-DD");
            console.log(appointments[currentDate]);
            return appointments[currentDate] || [];
        };

        const getMonthData = (value: Moment) => {
            if (value.month() === 8) {
                return 1394;
            }
        };

        return {
            value,
            loaded,
            getEventsForDate,
            getMonthData,
        };
    },
});
</script>

<style scoped>
.events {
    list-style: none;
    margin: 0;
    padding: 0;
}
.events .ant-badge-status {
    overflow: hidden;
    white-space: nowrap;
    width: 100%;
    text-overflow: ellipsis;
    font-size: 12px;
}
.notes-month {
    text-align: center;
    font-size: 28px;
}
.notes-month section {
    font-size: 28px;
}
</style>
