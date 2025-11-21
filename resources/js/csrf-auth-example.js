// Frontend CSRF Authentication Example
// This shows how to authenticate with Laravel using CSRF cookies

class AuthService {
    constructor(baseURL = 'http://laravel_api_spa.test') {
        this.baseURL = baseURL;
        this.axios = axios.create({
            baseURL: this.baseURL,
            withCredentials: true, // Essential for cookies
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        });
    }

    // Step 1: Get CSRF cookie before making authenticated requests
    async getCsrfCookie() {
        await this.axios.get('/sanctum/csrf-cookie');
    }

    // Step 2: Login with CSRF protection
    async login(email, password) {
        // Get CSRF cookie first
        await this.getCsrfCookie();
        
        // Then make login request
        const response = await this.axios.post('/auth/login', {
            email,
            password
        });
        
        return response.data;
    }

    // Step 3: Make authenticated requests
    async getUser() {
        const response = await this.axios.get('/auth/user');
        return response.data;
    }

    // Logout
    async logout() {
        const response = await this.axios.post('/auth/logout');
        return response.data;
    }
}

// Usage example:
const auth = new AuthService();

// Login flow
try {
    await auth.login('user@example.com', 'password');
    const user = await auth.getUser();
    console.log('Logged in user:', user);
} catch (error) {
    console.error('Login failed:', error.response.data);
}