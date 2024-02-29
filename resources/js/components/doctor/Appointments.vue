<template>
    <!-- Appointment list -->
    <h2 class="font-extrabold mt-4 text-2xl text-center">
        Pending Appointment List
    </h2>
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

        <!-- Pending Appointments table -->
        <a-table
            :columns="pendingAppointmentColumns"
            :data-source="filteredPendingAppointments"
        >
            <!-- Table body -->
            <template #bodyCell="{ column, text, record, index }">
                <!-- Handle custom rendering for different columns -->
                <template v-if="column.dataIndex === 'no'">
                    {{ record.index }}
                </template>
                <template v-else-if="column.dataIndex === 'action'">
                    <span class="flex gap-2">
                        <a-button @click="handleAccept(record)">Accept</a-button>
                        <a-button @click="handleReject(record)" danger>Reject</a-button>
                    </span>
                </template>
                <template v-else>
                    <a>{{ text }}</a>
                </template>
            </template>
        </a-table>
    </div>
    <!-- Accepted Appointment list -->
    <h2 class="font-extrabold mt-4 text-2xl text-center">
        Accepted Appointment List
    </h2>
    <div>
        <!-- Global search input -->
        <input
            v-model="searchTextAccepted"
            type="text"
            placeholder="Search..."
            class="p-2 border rounded-md mb-4"
        />

        <!-- Column-wise filters -->
        <div class="flex mb-4">
            <div class="mr-4">
                <label class="font-bold">Filter by Gender:</label>
                <select v-model="genderFilterAccepted" class="p-2 border rounded-md">
                    <option value="">All</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
            </div>
            <div class="mr-4">
                <label class="font-bold">Filter by Age:</label>
                <input
                    v-model.number="ageFilterAccepted"
                    type="number"
                    min="1"
                    class="p-2 border rounded-md"
                />
            </div>
            <div>
                <label class="font-bold">Filter by Support:</label>
                <select v-model="supportFilterAccepted" class="p-2 border rounded-md">
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
        <!-- Accepted Appointments table -->
        <a-table
            :columns="acceptedAppointmentColumns"
            :data-source="filteredAcceptedAppointments"
        >
            <!-- Table body -->
            <template #bodyCell="{ column, text, record, index }">
                <!-- Handle custom rendering for different columns -->
                <template v-if="column.dataIndex === 'no'">
                    {{ record.index }}
                </template>
                <template v-else-if="column.dataIndex === 'action'">
                    <span class="flex gap-2">
                        <a-button @click="handleAccept(record)">Accept</a-button>
                        <a-button @click="handleReject(record)" danger>Reject</a-button>
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
import { defineComponent, ref, onMounted, reactive, createVNode, computed } from "vue";
import axios from "axios";
const pendingAppointmentColumns = [
    {
        title: "No",
        dataIndex: "no",
        key: "no",
        slots: { customRender: "no" },
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
const acceptedAppointmentColumns = [
    {
        title: "No",
        dataIndex: "no",
        key: "no",
        slots: { customRender: "no" },
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


const userID = localStorage.getItem("UserID");
export default defineComponent({
    setup() {
        const supports = ref([]);
        const pendingAppointments = ref([]);
        const acceptedAppointments = ref([]);

        const searchText = ref("");
        const genderFilter = ref("");
        const ageFilter = ref(null);
        const supportFilter = ref("");
        const searchTextAccepted = ref("");
        const genderFilterAccepted = ref("");
        const ageFilterAccepted = ref(null);
        const supportFilterAccepted = ref("");
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
                const response = await axios.get(
                    `/api/doctor-pending-appointments/${userID}`
                );
                pendingAppointments.value = response.data.map(
                    (appointment, no) => ({ ...appointment, no: no + 1 })
                );
                // console.log(pendingAppointments);
            } catch (error) {
                console.error("Error fetchAppointment: ", error);
            }
        };
        const fetchAcceptedAppointment = async () => {
            try {
                // Fetch accepted appointments data from API
                // Modify the URL according to your API endpoint
                const response = await axios.get(
                    `/api/doctor-accepted-appointments/${userID}`
                );
                acceptedAppointments.value = response.data.map(
                    (appointment, no) => ({ ...appointment, no: no + 1 })
                );
            } catch (error) {
                console.error("Error fetchAcceptedAppointment: ", error);
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
        onMounted(() => {
            fetchPendingAppointment();
            fetchAcceptedAppointment();
            fetchSupport();
        });
        const handleAccept = async (record) => {
            const response = await axios.put(`/api/appointment/${record.id}/accepted`);
            fetchPendingAppointment();
            fetchAcceptedAppointment();
        }
        const handleReject = async (record) => {
            const response = await axios.put(`/api/appointment/${record.id}/rejected`);
            fetchPendingAppointment();
            fetchAcceptedAppointment();
        }
        // Computed property to filter appointments based on global search text, gender, age, doctor, and support
        const filteredPendingAppointments = computed(() => {
            return pendingAppointments.value.filter((appointment) => {
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
                if (ageFilter.value !== null && ageFilter.value !== "") {
                    if (appointment.age !== parseInt(ageFilter.value))
                        return false;
                }

                // Check support filter
                if (
                    supportFilter.value &&
                    appointment.support.id !== supportFilter.value
                )
                    return false;

                return true; // Include the appointment if it passes all filters
            });
        });
        const filteredAcceptedAppointments = computed(() => {
            return acceptedAppointments.value.filter((appointment) => {
                // Check global search text
                if (searchTextAccepted.value.trim()) {
                    const search = searchTextAccepted.value.toLowerCase();
                    const matchSearchText = Object.values(appointment).some(
                        (value) => String(value).toLowerCase().includes(search)
                    );
                    if (!matchSearchText) return false;
                }

                // Check gender filter
                if (
                    genderFilterAccepted.value &&
                    appointment.gender !== genderFilterAccepted.value
                )
                    return false;

                // Check age filter
                if (ageFilterAccepted.value !== null && ageFilterAccepted.value !== "") {
                    if (appointment.age !== parseInt(ageFilterAccepted.value))
                        return false;
                }

                // Check support filter
                if (
                    supportFilterAccepted.value &&
                    appointment.support.id !== supportFilterAccepted.value
                )
                    return false;

                return true; // Include the appointment if it passes all filters
            });
        });
        return {
            visible,
            showModal,
            handleOk,
            editMode,
            pendingAppointmentColumns,
            acceptedAppointmentColumns,
            pendingAppointments,
            supports, 
            searchText,
            genderFilter,
            ageFilter,
            supportFilter,
            filteredPendingAppointments,
            handleAccept,
            handleReject,
            acceptedAppointments,
            searchTextAccepted,
            genderFilterAccepted,
            ageFilterAccepted,
            supportFilterAccepted,
            filteredAcceptedAppointments,
        };
    },
    components: {},
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
