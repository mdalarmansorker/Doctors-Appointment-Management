<template>
    <a-calendar v-model:value="value">
      <template #dateCellRender="{ current: value }">
        <ul class="events">
          <li v-for="item in getListData(value)" :key="item.id">
            <a-badge :status="item.type" :text="item.content" />
          </li>
        </ul>
      </template>
      <template #monthCellRender="{ current: value }">
        <div v-if="getMonthData(value)" class="notes-month">
          <section>{{ getMonthData(value) }}</section>
          <span>Backlog number</span>
        </div>
      </template>
    </a-calendar>
  </template>
  
  <script lang="ts">
  import { defineComponent, ref } from 'vue';
  import axios from 'axios';
  import { Moment } from 'moment';
  
  export default defineComponent({
    setup() {
      const value = ref<Moment>();
      const events = ref([]);
        const doctorID = localStorage.getItem('userID');
      const getListData = async (value: Moment) => {
        console.log(value.format('YYYY-MM-DD'));
        try {
          const response = await axios.get(`/api/appointments/${doctorID}/${value.format('YYYY-MM-DD')}`);
          events.value = response.data;
          console.log(value.format('YYYY-MM-DD'), response);

        } catch (error) {
          console.error('Error fetching events:', error);
        }
        return events.value;
      };
  
      const getMonthData = (value: Moment) => {
        if (value.month() === 8) {
          return 1394;
        }
      };
  
      return {
        value,
        getListData,
        getMonthData,
      };
    },
  });
  </script>
  
  <style scoped>
  .events {
    list-style: none;
    margin: 0;
    padding: 0;
  }
  .events .ant-badge-status {
    overflow: hidden;
    white-space: nowrap;
    width: 100%;
    text-overflow: ellipsis;
    font-size: 12px;
  }
  .notes-month {
    text-align: center;
    font-size: 28px;
  }
  .notes-month section {
    font-size: 28px;
  }
  </style>
  