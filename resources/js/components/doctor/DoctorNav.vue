<template>

    <a-menu
      v-model:openKeys="openKeys"
      v-model:selectedKeys="selectedKeys"
      mode="horizontal"
      @click="handleClick"
    >
      <a-menu-item key="1">
        <template #icon>
          <HomeOutlined />
        </template>
        Appointments
      </a-menu-item>
      <a-menu-item key="2">
        <template #icon>
          <CalendarOutlined />
        </template>
        Calendar
      </a-menu-item>
    </a-menu>
  <div class="items-center">
    <template v-if="selectedTemplate === 'Appointments'">
      <Appointments />
    </template>
    <template v-else>
      <Calendar />
    </template>
  </div>
  
  </template>
  
  <script lang="ts">
  import { defineComponent, reactive, ref } from 'vue';
  import {
    HomeOutlined,
    UsergroupAddOutlined,
    CalendarOutlined,
    UserOutlined,
    TagOutlined,
  } from '@ant-design/icons-vue';
  import type { MenuProps } from 'ant-design-vue';
  import Calendar from './Calendar.vue';
  import Appointments from './Appointments.vue';
  
  export default defineComponent({
    components: {
      HomeOutlined,
      UsergroupAddOutlined,
      CalendarOutlined,
      UserOutlined,
      TagOutlined,
      Calendar, 
      Appointments,
    },
    setup() {
      const state = reactive({
        selectedKey: null,
        openKeys: [],
      });
      const selectedTemplate = ref('Home'); // Default template to show
  
      const handleClick: MenuProps['onClick'] = menuInfo => {
        switch (menuInfo.key) {
          case '1':
            selectedTemplate.value = 'Appointments';
            break;
          case '2':
            selectedTemplate.value = 'Calendar';
            break;
          default:
            selectedTemplate.value = 'Appointments';
        }
      };
  
      return {
        selectedTemplate,
        state,
        handleClick,
      };
    },
  });
  </script>
  