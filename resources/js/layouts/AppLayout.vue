<template>
    <header class="border-bottom bg-light">
        <nav class="container py-3
                    d-flex flex-wrap
                    align-items-center gap-3">

            <RouterLink
                :to="{ name: 'home' }"
                class="navbar-brand fw-semibold"
            >
                Велопарковки
            </RouterLink>

            <div class="nav nav-pills gap-2">
                <RouterLink
                    :to="{ name: 'home' }"
                    class="nav-link"
                    exact-active-class="active"
                >
                    Головна
                </RouterLink>

                <RouterLink
                    :to="{ name: 'bike-racks.index' }"
                    class="nav-link"
                    active-class="active"
                >
                    Велопарковки
                </RouterLink>
            </div>

            <a
                href="/bike-racks"
                class="btn btn-outline-secondary ms-auto"
            >
                Blade-інтерфейс
            </a>
        </nav>
    </header>

    <main class="container py-4">
        <RouterView />
    </main>
</template>