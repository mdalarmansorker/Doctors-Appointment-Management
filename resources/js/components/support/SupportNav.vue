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
                        :min="minDate"
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
                    <select
                        v-model="form.doctorID"
                        required
                        class="p-4 rounded-md"
                    >
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
                    <button
                        type="submit"
                        class="btn btn-primary text-blue-600 hover:text-white"
                    >
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
    <div>
        <!-- Global search input -->
        <input
            v-model="searchText"
            type="text"
            placeholder="Search..."
            class="p-2 border rounded-md mb-4"
        />

        <!-- Column-wise filters -->
        <div class="flex mb-4">
            <div class="mr-4">
                <label class="font-bold">Filter by Gender:</label>
                <select v-model="genderFilter" class="p-2 border rounded-md">
                    <option value="">All</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div class="mr-4">
                <label class="font-bold">Filter by Age:</label>
                <input
                    v-model.number="ageFilter"
                    type="number"
                    min="1"
                    class="p-2 border rounded-md"
                />
            </div>
            <div class="mr-4">
                <label class="font-bold">Filter by Doctor:</label>
                <select v-model="doctorFilter" class="p-2 border rounded-md">
                    <option value="">All</option>
                    <option
                        v-for="doctor in doctors"
                        :key="doctor.id"
                        :value="doctor.id"
                    >
                        {{ doctor.name }}
                    </option>
                </select>
            </div>
            <div>
                <label class="font-bold">Filter by Support:</label>
                <select v-model="supportFilter" class="p-2 border rounded-md">
                    <option value="">All</option>
                    <option
                        v-for="support in supports"
                        :key="support.id"
                        :value="support.id"
                    >
                        {{ support.name }}
                    </option>
                </select>
            </div>
        </div>

        <!-- Appointments table -->
        <a-table
            :columns="appointmentColumns"
            :data-source="filteredAppointments"
        >
            <!-- Table body -->
            <template #bodyCell="{ column, text, record, index }">
                <!-- Handle custom rendering for different columns -->
                <template v-if="column.dataIndex === 'no'">
                    {{ record.index }}
                </template>
                <template v-else-if="column.dataIndex === 'status'">
                    <a-tag :color="getStatusColor(text)">{{
                        formatStatus(text)
                    }}</a-tag>
                </template>
                <template v-else-if="column.dataIndex === 'action'">
                    <span
                        v-if="
                            userID === record.support.id &&
                            record.status === 'pending'
                        "
                        class="flex gap-2"
                    >
                        <a-button @click="handleEdit(record)">Edit</a-button>
                        <a-button
                            danger
                            @click="showAppointmentDeleteConfirm(record)"
                            >Delete</a-button
                        >
                    </span>
                </template>
                <template v-else>
                    <a>{{ text }}</a>
                </template>
            </template>
        </a-table>
    </div>
</template>

<script lang="ts">
import {
    defineComponent,
    ref,
    onMounted,
    reactive,
    createVNode,
    computed,
} from "vue";
import {
    HomeOutlined,
    UsergroupAddOutlined,
    CalendarOutlined,
    UserOutlined,
    TagOutlined,
} from "@ant-design/icons-vue";
import type { MenuProps } from "ant-design-vue";
import { Modal } from "ant-design-vue";

import DoctorList from "../DoctorList.vue";
import { ExclamationCircleOutlined } from "@ant-design/icons-vue";

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
const supports = ref([]);
const appointments = ref([]);
const searchText = ref("");
const genderFilter = ref("");
const ageFilter = ref(null);
const doctorFilter = ref("");
const supportFilter = ref("");
const userID = parseInt(localStorage.getItem("UserID"));
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
        // Compute minimum date
        const minDate = computed(() => {
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, "0"); // January is 0!
            const day = String(today.getDate()).padStart(2, "0");

            return `${year}-${month}-${day}`;
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
            editMode.value = false;
            form.doctorID = "";
            form.name = "";
            form.email = "";
            form.phone = "";
            form.gender = "";
            form.age = "";
            form.problem = "";
            form.date = "";
            form.time = "";
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
        const fetchSupport = async () => {
            try {
                const response = await axios.get("/api/supports");
                supports.value = response.data;
            } catch (error) {
                console.error("Error fetchSupport: ", error);
            }
        };
        const fetchAppointment = async () => {
            try {
                const response = await axios.get("/api/appointments");
                appointments.value = response.data.map((appointment, no) => ({
                    ...appointment,
                    no: no + 1,
                }));
            } catch (error) {
                console.error("Error fetchAppointment: ", error);
            }
        };
        const showAppointmentDeleteConfirm = (record) => {
            Modal.confirm({
                title: "Are you sure deleting this appointment?",
                icon: createVNode(ExclamationCircleOutlined),
                content: "It cannot be undone!",
                okText: "Yes",
                okType: "danger",
                cancelText: "No",
                onOk() {
                    console.log("Ok");
                    handleDeleteAppointment(record);
                },
                onCancel() {
                    console.log("Cancel");
                },
            });
        };
        const handleDeleteAppointment = async (record) => {
            try {
                await axios.delete(`/api/appointments/${record.id}`);
                fetchAppointment();
                console.log("Appointments deleted");
            } catch (error) {
                console.error("Error fetchAppointment: ", error);
            }
        };
        const handleEdit = (record) => {
            visible.value = true;
            editMode.value = true;
            editAppointmentId.value = record.id;
            form.supportID = userID;
            form.doctorID = record.doctorID;
            form.name = record.name;
            form.email = record.email;
            form.phone = record.phone;
            form.gender = record.gender;
            form.age = record.age;
            form.problem = record.problem;
            form.date = record.date;
            form.time = record.time;
        };
        onMounted(() => {
            fetchDoctor();
            fetchSupport();
            fetchAppointment();
        });
        // Computed property to filter appointments based on global search text, gender, age, doctor, and support
        const filteredAppointments = computed(() => {
            return appointments.value.filter((appointment) => {
                // Check global search text
                if (searchText.value.trim()) {
                    const search = searchText.value.toLowerCase();
                    const matchSearchText = Object.values(appointment).some(
                        (value) => String(value).toLowerCase().includes(search)
                    );
                    if (!matchSearchText) return false;
                }

                // Check gender filter
                if (
                    genderFilter.value &&
                    appointment.gender !== genderFilter.value
                )
                    return false;

                // Check age filter
                // Check age filter
                if (ageFilter.value !== null && ageFilter.value !== "") {
                    if (appointment.age !== parseInt(ageFilter.value))
                        return false;
                }

                // Check doctor filter
                if (
                    doctorFilter.value &&
                    appointment.doctor.id !== doctorFilter.value
                )
                    return false;

                // Check support filter
                if (
                    supportFilter.value &&
                    appointment.support.id !== supportFilter.value
                )
                    return false;

                return true; // Include the appointment if it passes all filters
            });
        });
        return {
            visible,
            showModal,
            handleOk,
            doctors,
            supports,
            form,
            submitForm,
            editMode,
            appointmentColumns,
            appointments,
            searchText,
            genderFilter,
            ageFilter,
            doctorFilter,
            supportFilter,
            filteredAppointments,
            userID,
            showAppointmentDeleteConfirm,
            handleEdit,
            minDate,
        };
    },
    components: {
        DoctorList, // Register the DoctorList component
        HomeOutlined,
        UsergroupAddOutlined,
        CalendarOutlined,
        UserOutlined,
        TagOutlined,
        ExclamationCircleOutlined,
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
