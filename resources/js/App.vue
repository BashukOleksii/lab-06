<script setup>
import { ref } from 'vue';

const title = 'Система управління велопарковками';

const showDetails = ref(false);

function toggleDetails() {
    showDetails.value = !showDetails.value;
}
</script>

<template>
    <RouterView />
</template>

<style scoped>
h1 {
    font-weight: 600;
}
</style>