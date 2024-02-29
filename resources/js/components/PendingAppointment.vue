<template>
    <a-table :columns="appointmentColumns" :data-source="appointments">
        <template #bodyCell="{ column, text, record, index }">
            <template v-if="column.dataIndex === 'no'">
                {{ record.index }}
            </template>
            <template v-else-if="column.dataIndex === 'status'">
                <a-tag :color="getStatusColor(text)">{{
                    formatStatus(text)
                }}</a-tag>
            </template>
            <template v-else-if="column.dataIndex === 'action'">
                <span class="flex gap-2">
                    <a-button>Edit</a-button>
                    <a-button danger>Delete</a-button>
                </span>
            </template>
            <template v-else>
                <a>{{ text }}</a>
            </template>
        </template>
    </a-table>
</template>

<script language="ts">
import { defineComponent, ref, onMounted, reactive, createVNode } from "vue";
import axios from "axios";
const appointmentColumns = [
    {
        title: "No",
        dataIndex: "no",
        key: "no",
        slots: { customRender: "no" },
    },
    {
        title: "Doctor",
        dataIndex: ["doctor", "name"],
        key: "doctor_name",
        render: (doctorName) => doctorName,
    },
    {
        title: "Name",
        dataIndex: "name",
        key: "name",
    },
    {
        title: "Email",
        dataIndex: "email",
        key: "email",
    },
    {
        title: "Phone",
        dataIndex: "phone",
        key: "phone",
    },
    {
        title: "Gender",
        dataIndex: "gender",
        key: "gender",
    },
    {
        title: "Age",
        dataIndex: "age",
        key: "age",
    },
    {
        title: "Problem",
        dataIndex: "problem",
        key: "problem",
    },
    {
        title: "Date",
        dataIndex: "date",
        key: "date",
    },
    {
        title: "Time",
        dataIndex: "time",
        key: "time",
    },
    {
        title: "Status",
        dataIndex: "status",
        key: "status",
    },
    {
        title: "Support",
        dataIndex: ["support", "name"],
        key: "support_name",
    },
    {
        title: "Action",
        key: "action",
        dataIndex: "action",
    },
];
const appointments = ref([]);
const userID = localStorage.getItem("UserID");
</script>