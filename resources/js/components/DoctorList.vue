<template>
    <a-table :columns="doctorColumns" :data-source="doctors">
        <template #bodyCell="{ column, text, record, index }">
            <template v-if="column.dataIndex === 'index'">
                {{ record.index }}
            </template>
            <template v-else-if="column.dataIndex === 'active'">
              <a-tag>
                {{ formatActiveStatus(text) }}
              </a-tag>
            </template>
            <template v-else>
              <a>{{ text }}</a>
            </template>
        </template>
    </a-table>
</template>

<script lang="ts">
import { defineComponent, ref, onMounted } from "vue";
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

const doctors = ref([]);

export default defineComponent({
    setup() {
        const fetchDoctor = async () => {
            try {
                const response = await axios.get("/api/doctors");
                doctors.value = response.data.map((doctor, index) => ({
                    ...doctor,
                    index: index + 1,
                }));
            } catch (error) {
                console.error("Error fetchDoctor: ", error);
            }
        };

        onMounted(() => {
            fetchDoctor();
        });

        return {
            doctors,
            doctorColumns,
        };
    },
    methods:{
      formatActiveStatus(text) {
        return (text)?"Active":"Inactive";
      }
      
    }
});
</script>
