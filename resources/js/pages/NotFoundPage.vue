<template>
    <section class="text-center py-5">
        <h1 class="display-1 fw-bold">
            404
        </h1>

        <h2 class="h4 mb-3">
            Сторінку не знайдено
        </h2>

        <p class="text-body-secondary">
            Запитана сторінка не існує.
        </p>

        <RouterLink
            :to="{ name: 'home' }"
            class="btn btn-primary"
        >
            На головну
        </RouterLink>
    </section>
</template>