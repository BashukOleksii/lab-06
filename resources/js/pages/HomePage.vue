<template>
    <section>
        <h1 class="h3 mb-3">
            Система управління велопарковками
        </h1>

        <p class="text-body-secondary">
            Клієнтський інтерфейс системи управління
            велопарковками кварталів.
        </p>

        <RouterLink
            :to="{ name: 'bike-racks.index' }"
            class="btn btn-primary"
        >
            Перейти до велопарковок
        </RouterLink>
    </section>
</template>