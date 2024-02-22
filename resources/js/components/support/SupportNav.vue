<template>
    <div>
        <a-button type="primary" class="bg-blue-500" @click="showModal">Create Appointment</a-button>
        <a-modal v-model:visible="visible" title="Create Appointment" @ok="handleOk" :ok-button-props="{ type: 'default' }">
            <form method="post" @submit="submitForm">
                <input type="hidden" v-model="form.supportID" value="{{ userID }}">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Name</span>
                    </label>
                    <input id="name" v-model="form.name" type="text" placeholder="Name" class="input input-bordered bg-slate-100 border-blue-500 text-black" required />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Email</span>
                    </label>
                    <input id="email" v-model="form.email" type="email" placeholder="Email" class="input input-bordered bg-slate-100 border-blue-500 text-black" required />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Phone</span>
                    </label>
                    <input id="phone" v-model="form.phone" type="tel" placeholder="Phone no" class="input input-bordered bg-slate-100 border-blue-500 text-black" required />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Gender</span>
                    </label>
                    <select id="phone" v-model="form.gender" class="input input-bordered bg-slate-100 border-blue-500 text-black" required>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Age</span>
                    </label>
                    <input id="age" v-model="form.age" type="number" placeholder="Age" class="input input-bordered bg-slate-100 border-blue-500 text-black" required />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Problem</span>
                    </label>
                    <input id="problem" v-model="form.problem" type="text" placeholder="Problem" class="input input-bordered bg-slate-100 border-blue-500 text-black" />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Date</span>
                    </label>
                    <input id="date" v-model="form.date" type="date" placeholder="Date" class="input input-bordered bg-slate-100 border-blue-500 text-black" required />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Time</span>
                    </label>
                    <input id="time" v-model="form.time" type="time" placeholder="Time" class="input input-bordered bg-slate-100 border-blue-500 text-black" required />
                </div>
                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text text-black">Select Doctor</span>
                    </label>
                    <div class="radio-group">
                        <label v-for="doctor in doctors" :key="doctor.id" class="">
                            <input type="radio" v-model="form.doctorID" :value="doctor.id" class="" />
                            <span class=""></span>
                            {{ doctor.name }}
                        </label>
                    </div>
                </div>
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary">{{ editMode ? 'Update' : 'Create' }}</button>
                </div>
            </form>
        </a-modal>
    </div>

    <!-- Doctor list -->
    <h2 class="font-extrabold mt-4 text-2xl text-center">Doctor List</h2>
    <a-table :columns="doctorColumns" :data-source="doctors">
        <template #bodyCell="{ column, text }">
            <template v-if="column.dataIndex === 'name'">
                <a>{{ text }}</a>
            </template>
        </template>
    </a-table>
  </template>

<script lang="ts">
import { defineComponent, ref, onMounted, reactive, createVNode } from 'vue';
import axios from "axios";
const doctorColumns = [
    {
        title: 'Name',
        dataIndex: 'name',
        key: 'name',
    },
    {
        title: 'Email',
        dataIndex: 'email',
        key: 'email',
    }
];

const doctors = ref([]);
const userID = localStorage.getItem('UserID');
export default defineComponent({
setup() {
    const visible = ref<boolean>(false);
    const editMode = ref<boolean>(false);
    const editAppointmentId = ref<number | null>(null); 
    const form = reactive({
        supportID: '',
        doctorID: '',
        name: '',
        email: '',
        phone: '',
        gender: '',
        age: '',
        problem: '',
        date: '',
        time: ''
    });
    const submitForm = async (evt) => {
            evt.preventDefault();
            try {
                const url = editMode.value ? `/api/appointment/${editAppointmentId.value}` : '/api/appointment';
                const method = editMode.value ? 'put' : 'post';
                const response = await axios[method](url, form);
                console.log('Appointment created/updated:', response.data);
               
                visible.value = false; // Hide the modal after successful creation/update
                 // Fetch updated data to refresh the table
                editMode.value = false; // Reset edit mode
                editAppointmentId.value = null; // Reset edit role ID
            } catch (e) {
                if (e.response.data && e.response.data.errors) {
                    errors.value = Object.values(e.response.data.errors);
                }
            }
        };
    const showModal = () => {
    visible.value = true;
    };

    const handleOk = (e: MouseEvent) => {
    console.log(e);
    visible.value = false;
    };

    const fetchDoctor = async () => {
        try {
            const response = await axios.get('/api/doctors');
            doctors.value = response.data;
        }
        catch (error) {
            console.error('Error fetchDoctor: ', error);
        }
    };
    onMounted(() => {
        fetchDoctor();
    });
    return {
    userID,
    visible,
    showModal,
    handleOk,
    doctors,
    doctorColumns,
    form,
    submitForm, 
    editMode,
    
    };
},
});
</script>

