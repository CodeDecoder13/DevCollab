<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Globe } from 'lucide-vue-next';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { login, register } from '@/routes';

withDefaults(
    defineProps<{
        canRegister?: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <footer class="border-t border-border/50 bg-card/50 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                <div class="sm:col-span-2">
                    <Link href="/" class="flex items-center gap-2">
                        <AppLogoIcon class="h-7 w-7" />
                        <span class="text-lg font-bold tracking-tight">DevCollab</span>
                    </Link>
                    <p class="mt-3 max-w-sm text-sm leading-relaxed text-muted-foreground">
                        The modern project management platform built for development teams that ship great software.
                    </p>
                    <div class="mt-4 flex items-center gap-3 text-sm text-muted-foreground">
                        <Globe class="h-4 w-4" />
                        Built with Laravel, Vue, and Tailwind CSS
                    </div>
                </div>

                <div>
                    <h4 class="mb-4 text-sm font-semibold">Product</h4>
                    <div class="flex flex-col gap-3">
                        <Link href="/features" class="text-sm text-muted-foreground transition-colors hover:text-foreground">Features</Link>
                        <Link href="/solutions" class="text-sm text-muted-foreground transition-colors hover:text-foreground">Solutions</Link>
                        <Link href="/guide" class="text-sm text-muted-foreground transition-colors hover:text-foreground">Guide</Link>
                        <Link href="/about" class="text-sm text-muted-foreground transition-colors hover:text-foreground">About</Link>
                        <Link href="/contact" class="text-sm text-muted-foreground transition-colors hover:text-foreground">Contact</Link>
                    </div>
                </div>

                <div>
                    <h4 class="mb-4 text-sm font-semibold">Account</h4>
                    <div class="flex flex-col gap-3">
                        <Link :href="login()" class="text-sm text-muted-foreground transition-colors hover:text-foreground">Log in</Link>
                        <Link v-if="canRegister" :href="register()" class="text-sm text-muted-foreground transition-colors hover:text-foreground">Create Account</Link>
                    </div>
                </div>
            </div>

            <div class="mt-12 flex flex-col items-center justify-between gap-4 border-t border-border/50 pt-8 text-sm text-muted-foreground md:flex-row">
                <p>&copy; {{ new Date().getFullYear() }} DevCollab. All rights reserved.</p>
                <div class="flex items-center gap-6">
                    <span>Privacy Policy</span>
                    <span>Terms of Service</span>
                </div>
            </div>
        </div>
    </footer>
</template>
