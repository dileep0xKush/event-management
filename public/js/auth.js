const TOKEN_KEY = "auth_token";
const LOGIN_PAGE = "/";
const DASHBOARD_PAGE = "/dashboard";

function getToken() {
    return localStorage.getItem(TOKEN_KEY);
}

function removeToken(redirect = true) {
    localStorage.removeItem(TOKEN_KEY);
    if (redirect) {
        window.location.href = LOGIN_PAGE;
    }
}

(() => {
    const token = getToken();
    const path = window.location.pathname;

    if (!token && path !== LOGIN_PAGE) {
        window.location.href = LOGIN_PAGE;
    }

    if (token && path === LOGIN_PAGE) {
        window.location.href = DASHBOARD_PAGE;
    }
})();

async function apiFetch(url, options = {}) {
    const token = getToken();
    const headers = {
        "Accept": "application/json",
        ...options.headers,
    };
    if (token) {
        headers["Authorization"] = `Bearer ${token}`;
    }

    try {
        const response = await fetch(url, {
            ...options,
            headers,
        });

        if (response.status === 401) {
            removeToken();
            return null;
        }

        return response;
    } catch (error) {
        console.error("API Error:", error);
        removeToken();
        return null;
    }
}

async function checkAuthOnPageLoad() {
    const token = getToken();
    if (!token) return;

    const response = await apiFetch("/api/user");

    if (response && response.ok) {
    } else {
        removeToken();
    }
}

document.addEventListener("DOMContentLoaded", checkAuthOnPageLoad);
