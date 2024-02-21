<template>
    <div>
        <a-button  @click="showModal">Create Role</a-button>
        <a-modal v-model:visible="visible" title="Create Role" @ok="handleOk" :ok-button-props="{ type: 'default' }">
        <form method="post" @submit="submitForm">
            <div class="form-control">
            <label class="label">
                <span class="label-text text-black">Role Name</span>
            </label>
            <input id="name" v-model="form.name" type="text" placeholder="Role Name" class="input input-bordered bg-slate-100 border-blue-500 text-black" required />
            </div>
            <div class="form-control mt-6">
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>

        </a-modal>
    </div>
    <a-table :data-source="data">
      <a-table-column title="#" width="50px">
        <template #default="{ record, index }">
          {{ index + 1 }}
        </template>
      </a-table-column>
      <a-table-column key="name" title="Role Name" data-index="name" />
      <a-table-column key="action" title="Action">
        <template #default="{ text, record, index }">
          <span>
            <a @click="handleEdit(record)">Edit</a>
            <a-divider type="vertical" />
            <a-button danger @click="showDeleteConfirm">Delete</a-button>
          </span>
        </template>
      </a-table-column>
    </a-table>
  </template>
  
  <script lang="ts">
  import { defineComponent, ref, onMounted, createVNode, reactive } from 'vue';
  import { ExclamationCircleOutlined } from '@ant-design/icons-vue';
  import { Modal } from 'ant-design-vue';

  import axios from 'axios';
  
  export default defineComponent({
    setup() {
        const data = ref([]);
        const errors = ref();
        const fetchData = async () => {
        try {
            const response = await axios.get('/api/roles');
            data.value = response.data;
        } catch (error) {
            console.error('Error fetching roles:', error);
        }
        };

        const handleEdit = (record) => {
        // Handle edit logic here
        console.log('Edit role:', record);
        };
        
        // Fetch data when the component is mounted
        onMounted(() => {
            fetchData(); 
        });
        const handleDelete = (record) => {
        // Handle delete logic here
        console.log('Delete role:', record);
        };
        const visible = ref<boolean>(false);

        const showModal = () => {
            visible.value = true;
        };
        const roleName = ref('');
        const handleOk = (e: MouseEvent) => {
            console.log(e);
            visible.value = false;
        };
        const form = reactive({
            name: '',
        })
        const submitForm = async (evt) => {
            evt.preventDefault()
            try {
                const response = await axios.post('/api/roles', form);
                console.log('Role created:', response.data);
                visible.value = false; // Hide the modal after successful creation
                fetchData(); // Fetch updated data to refresh the table
            } catch (e) {
                if(e.response.data && e.response.data.errors) {
                    errors.value = Object.values(e.response.data.errors)
                }
            }
        };
        const showDeleteConfirm = () => {
        Modal.confirm({
            title: 'Are you sure delete this role?',
            icon: createVNode(ExclamationCircleOutlined),
            content: 'Some descriptions',
            okText: 'Yes',
            okType: 'danger',
            cancelText: 'No',
            onOk() {
                console.log('OK');
            },
            onCancel() {
                console.log('Cancel');
            },
            });
        };
        return {
        data,
        handleEdit,
        handleDelete,
        showDeleteConfirm,
        visible,
        showModal,
        handleOk,
        form,
        errors,
        submitForm,
        };
    },
  });
  </script>
  