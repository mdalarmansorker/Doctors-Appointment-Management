<template>
    <div v-if="loaded">
        <a-calendar v-model:value="value">
            <template #dateCellRender="{ current: value }">
                <ul class="events">
                    <li v-for="item in getEventsForDate(value)" :key="item.id">
                        <!-- <a-badge :status="'warning'" :text="item['name']" /> -->
                        <a-button @click="showModal">{{ item.name }} {{ item.time }}</a-button>
                        <a-modal
                            v-model:visible="visible"
                            title="Appointment Details"
                            @ok="handleOk"
                            :ok-button-props="{ type: 'default' }"
                        >
                            <p class="text-md font-bold">Name: {{ item.name }}</p>
                            <p>Email: {{ item.email }}</p>
                            <p>Phone: {{ item.phone }}</p>
                            <p>Gender: {{ item.gender }}</p>
                            <p>Age: {{ item.age }}</p>
                            <p>Problem: {{ item.problem }}</p>
                            <p>Date: {{ item.date }}</p>
                            <p>Time: {{ item.time }}</p>
                        </a-modal>
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
import { Modal } from "ant-design-vue";
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
                `/api/doctor-accepted-appointments/${doctorID}`
            );
            events = response.data;
            // console.log(doctorID);
            loaded.value = true;
            console.log(events);
            for (let item of events) {
                if (!appointments[item.date]) {
                    appointments[item.date] = [];
                }
                appointments[item.date].push(item);
                console.log("app", appointments);
            }
        };

        onMounted(fetchAppointments);

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
        const visible = ref<boolean>(false);

        const showModal = () => {
            visible.value = true;
        };

        const handleOk = (e: MouseEvent) => {
            console.log(e);
            visible.value = false;
        };
        return {
            value,
            loaded,
            getEventsForDate,
            getMonthData,
            visible,
            showModal,
            handleOk,
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
