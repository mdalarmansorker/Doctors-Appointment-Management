<template>
    <!-- Appointment list -->
    <h2 class="font-extrabold mt-4 text-2xl text-center">Pending Appointment List</h2>
    <a-table :columns="appointmentColumns" :data-source="pendingAppointments">
      <template #bodyCell="{ column, text, record, index }">
        <template v-if="column.dataIndex !== 'no'">
          <a>{{ text }}</a>
        </template>
        <template v-else>
          {{ record.index }}
        </template>
      </template>
    </a-table>

    <!-- Calendar -->
    <Calendar />
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, reactive, createVNode } from 'vue';
import Calendar from './Calendar.vue';
import axios from "axios";
const appointmentColumns = [
    {
        title: 'No',
        dataIndex: 'no',
        key: 'no',
        slots: { customRender: 'no' },
    },
    {
        title: 'Name',
        dataIndex: 'name',
        key: 'name',
    },
    {
        title: 'Email',
        dataIndex: 'email',
        key: 'email',
    },
    {
        title: 'Phone',
        dataIndex: 'phone',
        key: 'phone',
    },
    {
        title: 'Gender',
        dataIndex: 'gender',
        key: 'gender',
    },
    {
        title: 'Age',
        dataIndex: 'age',
        key: 'age',
    },
    {
        title: 'Problem',
        dataIndex: 'problem',
        key: 'problem',
    },
    {
        title: 'Date',
        dataIndex: 'date',
        key: 'date',
    },
    {
        title: 'Time',
        dataIndex: 'time',
        key: 'time',
    },
    {
        title: 'Support',
        dataIndex: ['support', 'name'],
        key: 'support_name',
    },
];

const doctors = ref([]);
const pendingAppointments = ref([]);
const userID = localStorage.getItem('UserID');
export default defineComponent({
setup() {
    const visible = ref<boolean>(false);
    const editMode = ref<boolean>(false);
    const editAppointmentId = ref<number | null>(null); 
    
    
    const showModal = () => {
    visible.value = true;
    };

    const handleOk = (e: MouseEvent) => {
    console.log(e);
    visible.value = false;
    };

    const fetchPendingAppointment = async () => {
        try {
            const response = await axios.get(`/api/doctor-pending-appointments/${userID}`);
            pendingAppointments.value = response.data.map((appointment, no) => ({ ...appointment, no: no + 1}));
            console.log(pendingAppointments);
        } catch (error) {
            console.error('Error fetchAppointment: ', error);
        }
    };
    
    onMounted(() => {
        fetchPendingAppointment();
    });
    return {
    visible,
    showModal,
    handleOk,
    doctors,
    editMode,
    appointmentColumns,
    pendingAppointments,
    };
},
components: {
    Calendar,
}
});
</script>

