export function getHttpError(error) {
    const status = error.response?.status ?? null;

    const data = error.response?.data;

    const serverMessage =
        typeof data?.message === 'string'
            ? data.message
            : null;

    const messages = {
        401: 'Необхідно увійти в систему.',

        403: 'У вас немає прав для виконання цієї дії.',

        404: 'Запитаний ресурс не знайдено.',

        409: 'Операція конфліктує з поточним станом ресурсу.',

        419: 'Сесія або CSRF-токен застаріли.',

        422: 'Перевірте правильність введених даних.',

        429: 'Забагато запитів. Спробуйте пізніше.',
    };

    let message;

    if (status === null) {
        message = error.code === 'ECONNABORTED'
            ? 'Сервер не відповів вчасно.'
            : 'Не вдалося встановити зв’язок із сервером.';
    } else if (status >= 500) {
        message = 'Виникла внутрішня помилка сервера.';
    } else if (status === 409) {
        message = serverMessage ?? messages[409];
    } else {
        message = messages[status]
            ?? serverMessage
            ?? 'Не вдалося виконати запит.';
    }

    return {
        status,

        message,

        errors: status === 422
            ? (data?.errors ?? {})
            : {},
    };
}