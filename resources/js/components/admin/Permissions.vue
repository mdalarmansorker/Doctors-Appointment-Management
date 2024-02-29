<template>
    <div>
        <a-button  @click="showModal" class="mt-4">Create Permission</a-button>
        <a-modal v-model:visible="visible" title="Create Permission" @ok="handleOk" :ok-button-props="{ type: 'default' }">
            <form method="post" @submit="submitForm">
            <div class="form-control">
                <label class="label">
                    <span class="label-text text-black">Permission Name</span>
                </label>
                <input id="name" v-model="form.name" type="text" placeholder="Permission Name" class="input input-bordered bg-slate-100 border-blue-500 text-black" required />
            </div>
            <!-- <div class="form-control mt-4">
                <label class="label">
                    <span class="label-text text-black">Select Roles</span>
                </label>
                <div class="checkbox-group">
                    <label v-for="role in roles" :key="role.id" class="">
                        <input type="checkbox" v-model="form.roles" :value="role.id" class="" />
                        <span class=""></span>
                        {{ role.name }}
                    </label>
                </div>
            </div> -->
            <div class="form-control mt-6">
                <button type="submit" class="btn btn-primary">{{ editMode ? 'Update' : 'Create' }}</button>
            </div>
        </form>

        </a-modal>
    </div>
    <a-table :data-source="permission">
      <a-table-column title="#" width="50px">
        <template #default="{ record, index }">
          {{ index + 1 }}
        </template>
      </a-table-column>
      <a-table-column key="name" title="Permission Name" data-index="name" />
      <a-table-column key="action" title="Action">
        <template #default="{ text, record, index }">
        <span class="flex gap-2">
            <a-button @click="handleEdit(record)">Edit</a-button>
            <a-button danger @click="showDeleteConfirm(record)">Delete</a-button>
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
        const permission = ref([]);
        const roles = ref([]);
        const visible = ref<boolean>(false);
        const errors = ref();
        const editMode = ref<boolean>(false); // Track if the form is in edit mode
        const editPermissionId = ref<number | null>(null); // Track the ID of the permission being edited
        const form = reactive({
            name: '',
            // roles: []
        })        
        const fetchPermission = async () => {
        try {
            const response = await axios.get('/api/permissions');
            permission.value = response.data;
        } catch (error) {
            console.error('Error fetching permissions:', error);
        }
        };
        const fetchRole = async () => {
            try {
                const response = await axios.get('/api/roles');
                roles.value = response.data;
                console.log(roles);
            } catch (error) {
                console.error('Error fetching roles:', error);
            }
        };
        const handleEdit = (record) => {
            editPermissionId.value = record.id;
            form.name = record.name;
            // form.roles = record.roles.map(role => role.id);
            visible.value = true;
            editMode.value = true;
        };
        
        // Fetch data when the component is mounted
        onMounted(() => {
            fetchPermission();
            fetchRole();
        });

        const showModal = () => {
            form.name = '';
            form.roles = [];
            editMode.value = false;
            visible.value = true;
            console.log('Show modal:', visible);
        };
        const handleOk = (e: MouseEvent) => {
            console.log(e);
            visible.value = false;
        };

        const submitForm = async (evt) => {
            evt.preventDefault()
            try {
                const url = editMode.value ? `/api/permissions/${editPermissionId.value}` : '/api/roles';
                const method = editMode.value ? 'put' : 'post';
                const response = await axios[method](url, form);
                console.log('Permission created/updated:', response.data);
               
                visible.value = false; // Hide the modal after successful creation/update
                fetchPermission(); // Fetch updated data to refresh the table
                editMode.value = false; // Reset edit mode
                editPermissionId.value = null; // Reset edit role ID
            } catch (e) {
                if (e.response.data && e.response.data.errors) {
                    errors.value = Object.values(e.response.data.errors);
                }
            }
        };
        const showDeleteConfirm = (record) => {
            Modal.confirm({
                title: 'Are you sure delete this permission?',
                icon: createVNode(ExclamationCircleOutlined),
                content: 'This action cannot be undone!',
                okText: 'Yes',
                okType: 'danger',
                cancelText: 'No',
                onOk() {
                    console.log('OK');
                    handleDelete(record);
                },
                onCancel() {
                    console.log('Cancel');
                },
                });
        };
        const handleDelete = async (record) => {
            try {
                await axios.delete(`/api/permissions/${record.id}`);
                fetchPermission(); // Fetch updated data to refresh the table
                console.log('Permission deleted successfully');
            } catch (error) {
                console.error('Error deleting permission:', error);
            }
        };
        return {
        permission,
        roles,
        handleEdit,
        handleDelete,
        showDeleteConfirm,
        visible,
        showModal,
        handleOk,
        form,
        errors,
        submitForm,
        editMode,
        };
    },
  });
  </script>
  