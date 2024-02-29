<template>
    <div>
        <!-- Filter controls -->
        <div class="flex mb-4">
            <div class="mr-4">
                <label class="font-bold">Filter by Name:</label>
                <input v-model="nameFilter" type="text" class="p-2 border rounded-md">
            </div>
            <div>
                <label class="font-bold">Filter by Active Status:</label>
                <select v-model="activeFilter" class="p-2 border rounded-md">
                    <option value="">All</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
        </div>

        <!-- Doctor table -->
        <a-table :columns="doctorColumns" :data-source="filteredDoctors">
            <!-- Body cell template -->
            <template #bodyCell="{ column, text, record, index }">
                <!-- Custom rendering for index -->
                <template v-if="column.dataIndex === 'index'">
                    {{ record.index }}
                </template>
                <!-- Custom rendering for active status -->
                <template v-else-if="column.dataIndex === 'active'">
                    <a-tag>{{ formatActiveStatus(text) }}</a-tag>
                </template>
                <!-- Default rendering for other columns -->
                <template v-else>
                    <a>{{ text }}</a>
                </template>
            </template>
        </a-table>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted, computed } from "vue";
import axios from "axios";

const doctorColumns = [
    {
        title: "Index",
        dataIndex: "index",
        key: "index",
        slots: { customRender: "index" }, // Render custom content for this column
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
        title: "Active",
        dataIndex: "active",
        key: "active",
    }
];

export default defineComponent({
    setup() {
        const doctors = ref([]);
        const nameFilter = ref("");
        const activeFilter = ref("");

        const fetchDoctors = async () => {
            try {
                const response = await axios.get("/api/doctors");
                doctors.value = response.data.map((doctor, index) => ({
                    ...doctor,
                    index: index + 1,
                }));
            } catch (error) {
                console.error("Error fetchDoctors: ", error);
            }
        };

        onMounted(() => {
            fetchDoctors();
        });

        // Computed property to filter doctors based on name and active status
        const filteredDoctors = computed(() => {
            return doctors.value.filter((doctor) => {
                // Check name filter
                if (nameFilter.value && !doctor.name.toLowerCase().includes(nameFilter.value.toLowerCase())) {
                    return false;
                }

                // Check active status filter
                if (activeFilter.value !== "" && doctor.active !== (activeFilter.value === "1")) {
                    return false;
                }

                return true; // Include the doctor if it passes all filters
            });
        });

        // Method to format active status
        const formatActiveStatus = (active) => {
            return active ? "Active" : "Inactive";
        };

        return {
            doctorColumns,
            filteredDoctors,
            nameFilter,
            activeFilter,
            formatActiveStatus,
        };
    },
});
</script>
