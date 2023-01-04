<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Link, useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
        <BreezeValidationErrors class="mb-4" />

        <form @submit.prevent="submit">
            <div>
                <label for="register-name" class="form-label">Name</label>
                <input v-model="form.name" required autocomplete="name" type="text" class="form-control" id="register-name">
            </div>

            <div class="mt-1">
                <label for="register-email" class="form-label">Email address</label>
                <input v-model="form.email" required autocomplete="username" type="email" class="form-control" id="register-email" aria-describedby="emailHelp">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>

            <div class="mt-1">
                <label for="register-password" class="form-label">Password</label>
                <input v-model="form.password" required autocomplete="new-password" type="password" class="form-control" id="register-password">
            </div>

            <div class="flex items-center justify-end mt-4">

                <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </BreezeButton>
            </div>
        </form>
</template>
