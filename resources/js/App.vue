<script setup>
import { ref } from 'vue';

const title = 'Система управління велопарковками';

const showDetails = ref(false);

function toggleDetails() {
    showDetails.value = !showDetails.value;
}
</script>

<template>
    <main class="container py-4">
        <h1 class="h3 mb-3">
            {{ title }}
        </h1>

        <div class="alert alert-success" role="status">
            Vue-застосунок успішно запущено.
        </div>

        <div class="d-flex flex-wrap gap-2 mb-3">
            <button
                type="button"
                class="btn btn-primary"
                @click="toggleDetails"
            >
                {{ showDetails
                    ? 'Приховати інформацію'
                    : 'Показати інформацію' }}
            </button>

            <a
                href="/bike-racks"
                class="btn btn-outline-secondary"
            >
                Blade-інтерфейс
            </a>
        </div>

        <div v-if="showDetails" class="card">
            <div class="card-body">
                <h2 class="h5">
                    Про застосунок
                </h2>

                <p class="mb-0">
                    Клієнтська частина реалізована
                    за допомогою Vue 3, а серверна —
                    за допомогою Laravel.
                </p>
            </div>
        </div>
    </main>
</template>

<style scoped>
h1 {
    font-weight: 600;
}
</style>