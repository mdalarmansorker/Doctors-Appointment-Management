<template>
    <div>
        <a-button type="primary" class="bg-blue-500" @click="showModal"
            >Create Appointment</a-button
        >
        <a-modal
            v-model:visible="visible"
            title="Create Appointment"
            @ok="handleOk"
            :ok-button-props="{ type: 'default' }"
            :mask-closable="false"
        >
            <form method="post" @submit="submitForm">
                <input id="supportID" type="hidden" v-model="form.supportID" />
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Name</span>
                    </label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        placeholder="Name"
                        class="input input-bordered bg-slate-100 border-blue-500 text-black"
                        required
                    />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Email</span>
                    </label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        placeholder="Email"
                        class="input input-bordered bg-slate-100 border-blue-500 text-black"
                        required
                    />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Phone</span>
                    </label>
                    <input
                        id="phone"
                        v-model="form.phone"
                        type="tel"
                        placeholder="Phone no"
                        class="input input-bordered bg-slate-100 border-blue-500 text-black"
                        required
                    />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Gender</span>
                    </label>
                    <div>
                        <label>
                            <input
                                type="radio"
                                v-model="form.gender"
                                value="Male"
                                required
                            />
                            <span class="ml-2">Male</span>
                        </label>
                        <label class="ml-4">
                            <input
                                type="radio"
                                v-model="form.gender"
                                value="Female"
                                required
                            />
                            <span class="ml-2">Female</span>
                        </label>
                    </div>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Age</span>
                    </label>
                    <input
                        id="age"
                        v-model="form.age"
                        type="number"
                        placeholder="Age"
                        class="input input-bordered bg-slate-100 border-blue-500 text-black"
                        required
                    />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Problem</span>
                    </label>
                    <input
                        id="problem"
                        v-model="form.problem"
                        type="text"
                        placeholder="Problem"
                        class="input input-bordered bg-slate-100 border-blue-500 text-black"
                    />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Date</span>
                    </label>
                    <input
                        id="date"
                        v-model="form.date"
                        type="date"
                        placeholder="Date"
                        class="input input-bordered bg-slate-100 border-blue-500 text-black"
                        required
                    />
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text text-black">Time</span>
                    </label>
                    <input
                        id="time"
                        v-model="form.time"
                        type="time"
                        placeholder="Time"
                        class="input input-bordered bg-slate-100 border-blue-500 text-black"
                        required
                    />
                </div>
                <div class="form-control mt-4">
                    <label class="label">
                        <span class="label-text text-black">Select Doctor</span>
                    </label>
                    <select v-model="form.doctorID" required class="p-4 rounded-md">
                        <option value="" disabled>Select Doctor</option>
                        <option
                            v-for="doctor in doctors"
                            :key="doctor.id"
                            :value="doctor.id"
                        >
                            {{ doctor.name }}
                        </option>
                    </select>
                </div>
                <div class="form-control mt-6">
                    <button type="submit" class="btn btn-primary text-blue-600 hover:text-white">
                        {{ editMode ? "Update" : "Create" }}
                    </button>
                </div>
            </form>
        </a-modal>
    </div>

    <!-- Doctor list -->
    <h2 class="font-extrabold mt-4 text-2xl text-center">Doctor List</h2>
    <DoctorList />
    <!-- Appointment list -->
    <h2 class="font-extrabold mt-4 text-2xl text-center">Appointment List</h2>
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

<script lang="ts">
import { defineComponent, ref, onMounted, reactive, createVNode } from "vue";
import {
    HomeOutlined,
    UsergroupAddOutlined,
    CalendarOutlined,
    UserOutlined,
    TagOutlined,
} from "@ant-design/icons-vue";
import type { MenuProps } from "ant-design-vue";
import DoctorList from "../DoctorList.vue";
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

const doctors = ref([]);
const appointments = ref([]);
const userID = localStorage.getItem("UserID");
export default defineComponent({
    setup() {
        const visible = ref<boolean>(false);
        const editMode = ref<boolean>(false);
        const editAppointmentId = ref<number | null>(null);
        const form = reactive({
            supportID: userID,
            doctorID: "",
            name: "",
            email: "",
            phone: "",
            gender: "",
            age: "",
            problem: "",
            date: "",
            time: "",
        });
        const submitForm = async (evt) => {
            evt.preventDefault();
            try {
                const url = editMode.value
                    ? `/api/appointment/${editAppointmentId.value}`
                    : "/api/appointment";
                const method = editMode.value ? "put" : "post";
                const response = await axios[method](url, form);
                console.log("Appointment created/updated:", response.data);
                fetchAppointment();
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
                const response = await axios.get("/api/doctors");
                doctors.value = response.data;
            } catch (error) {
                console.error("Error fetchDoctor: ", error);
            }
        };
        const fetchAppointment = async () => {
            try {
                const response = await axios.get("/api/appointment");
                appointments.value = response.data.map((appointment, no) => ({
                    ...appointment,
                    no: no + 1,
                }));
            } catch (error) {
                console.error("Error fetchAppointment: ", error);
            }
        };

        onMounted(() => {
            fetchDoctor();
            fetchAppointment();
        });
        return {
            visible,
            showModal,
            handleOk,
            doctors,
            form,
            submitForm,
            editMode,
            appointmentColumns,
            appointments,
        };
    },
    components: {
        DoctorList, // Register the DoctorList component
        HomeOutlined,
        UsergroupAddOutlined,
        CalendarOutlined,
        UserOutlined,
        TagOutlined,
    },
    methods: {
        getStatusColor(status) {
            if (status === "pending") {
                return "blue";
            } else if (status === "accepted") {
                return "green";
            } else {
                return "red";
            }
        },
        formatStatus(status) {
            return status.charAt(0).toUpperCase() + status.slice(1);
        },
    },
});
</script>
