<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { onUnmounted, ref } from 'vue';
import InputError from '@/components/InputError.vue';
import TextLink from '@/components/TextLink.vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { home, register } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

defineProps<{
    status?: string;
    canResetPassword: boolean;
    canRegister: boolean;
}>();

// Video carousel logic
const heroVideos = ['/herosection/vid-1.mp4', '/herosection/vid-2.mp4', '/herosection/vid-3.mp4'];
const activeVideoIndex = ref(0);
const videoRefA = ref<HTMLVideoElement | null>(null);
const videoRefB = ref<HTMLVideoElement | null>(null);
const showA = ref(true);
let videoTimer: ReturnType<typeof setTimeout> | null = null;

function scheduleNextVideo() {
    if (videoTimer) clearTimeout(videoTimer);
    videoTimer = setTimeout(() => {
        transitionToNext();
    }, 8000);
}

function transitionToNext() {
    const next = (activeVideoIndex.value + 1) % heroVideos.length;
    const incomingEl = showA.value ? videoRefB.value : videoRefA.value;
    if (incomingEl) {
        incomingEl.src = heroVideos[next];
        incomingEl.load();
        incomingEl.play().catch(() => {});
    }
    setTimeout(() => {
        showA.value = !showA.value;
        activeVideoIndex.value = next;
        setTimeout(() => {
            scheduleNextVideo();
        }, 1200);
    }, 300);
}

function onVideoReady() {
    scheduleNextVideo();
}

onUnmounted(() => {
    if (videoTimer) clearTimeout(videoTimer);
});
</script>

<template>
    <Head title="Log in" />

    <div class="relative grid min-h-dvh lg:grid-cols-2">
        <!-- Left side: Login form -->
        <div class="flex flex-col items-center justify-center p-6 md:p-10">
            <div class="w-full max-w-sm">
                <div class="flex flex-col gap-8">
                    <!-- Logo -->
                    <div class="flex flex-col items-center gap-4">
                        <Link
                            :href="home()"
                            class="flex flex-col items-center gap-2 font-medium lg:hidden"
                        >
                            <AppLogoIcon class="h-9 w-9" />
                        </Link>
                        <div class="space-y-2 text-center">
                            <h1 class="text-xl font-medium">Log in to your account</h1>
                            <p class="text-sm text-muted-foreground">
                                Enter your email and password below to log in
                            </p>
                        </div>
                    </div>

                    <div
                        v-if="status"
                        class="text-center text-sm font-medium text-green-600"
                    >
                        {{ status }}
                    </div>

                    <!-- Form -->
                    <Form
                        v-bind="store.form()"
                        :reset-on-success="['password']"
                        v-slot="{ errors, processing }"
                        class="flex flex-col gap-6"
                    >
                        <div class="grid gap-6">
                            <div class="grid gap-2">
                                <Label for="email">Email address</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    required
                                    autofocus
                                    :tabindex="1"
                                    autocomplete="email"
                                    placeholder="email@example.com"
                                />
                                <InputError :message="errors.email" />
                            </div>

                            <div class="grid gap-2">
                                <div class="flex items-center justify-between">
                                    <Label for="password">Password</Label>
                                    <TextLink
                                        v-if="canResetPassword"
                                        :href="request()"
                                        class="text-sm"
                                        :tabindex="5"
                                    >
                                        Forgot password?
                                    </TextLink>
                                </div>
                                <Input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    :tabindex="2"
                                    autocomplete="current-password"
                                    placeholder="Password"
                                />
                                <InputError :message="errors.password" />
                            </div>

                            <div class="flex items-center justify-between">
                                <Label for="remember" class="flex items-center space-x-3">
                                    <Checkbox id="remember" name="remember" :tabindex="3" />
                                    <span>Remember me</span>
                                </Label>
                            </div>

                            <Button
                                type="submit"
                                class="mt-2 w-full bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-md shadow-indigo-500/25 hover:shadow-lg hover:shadow-indigo-500/30 border-0"
                                :tabindex="4"
                                :disabled="processing"
                                data-test="login-button"
                            >
                                <Spinner v-if="processing" />
                                Log in
                            </Button>
                        </div>

                        <div
                            class="text-center text-sm text-muted-foreground"
                            v-if="canRegister"
                        >
                            Don't have an account?
                            <TextLink :href="register()" :tabindex="5">Sign up</TextLink>
                        </div>
                    </Form>
                </div>
            </div>
        </div>

        <!-- Right side: Video background (hidden on mobile, shown on lg+) -->
        <div class="relative hidden overflow-hidden bg-[#09090b] lg:block">
            <!-- Video Slot A -->
            <video
                ref="videoRefA"
                :src="heroVideos[0]"
                class="absolute inset-0 h-full w-full object-cover transition-opacity duration-[1200ms] ease-in-out"
                :class="showA ? 'opacity-100' : 'opacity-0'"
                autoplay
                muted
                loop
                playsinline
                @canplay="onVideoReady"
            />
            <!-- Video Slot B -->
            <video
                ref="videoRefB"
                class="absolute inset-0 h-full w-full object-cover transition-opacity duration-[1200ms] ease-in-out"
                :class="showA ? 'opacity-0' : 'opacity-100'"
                muted
                playsinline
            />

            <!-- Overlays -->
            <div class="absolute inset-0 bg-black/50" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/30" />
            <div class="absolute left-1/2 top-0 h-[400px] w-[600px] -translate-x-1/2 rounded-full bg-gradient-to-br from-indigo-500/20 via-violet-500/10 to-transparent blur-3xl" />

            <!-- Content overlay -->
            <div class="relative z-10 flex h-full flex-col justify-between p-10">
                <!-- Logo -->
                <Link :href="home()" class="flex items-center gap-2">
                    <AppLogoIcon class="h-8 w-8" />
                    <span class="text-lg font-bold text-white">DevCollab</span>
                </Link>

                <!-- Bottom text -->
                <div>
                    <blockquote class="space-y-4">
                        <p class="text-lg font-medium leading-relaxed text-white/90">
                            "We migrated from Jira in a weekend. DevCollab gives us everything we need without the bloat."
                        </p>
                        <footer class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-amber-500 to-orange-500 text-sm font-semibold text-white">
                                EZ
                            </div>
                            <div>
                                <div class="text-sm font-medium text-white">Emily Zhang</div>
                                <div class="text-sm text-white/60">CTO, DataPulse</div>
                            </div>
                        </footer>
                    </blockquote>
                </div>
            </div>

            <!-- Video indicators -->
            <div class="absolute bottom-10 left-1/2 z-10 flex -translate-x-1/2 gap-2">
                <div
                    v-for="(_, index) in heroVideos"
                    :key="index"
                    class="relative h-1 overflow-hidden rounded-full transition-all duration-300"
                    :class="activeVideoIndex === index ? 'w-8 bg-white/30' : 'w-4 bg-white/15'"
                >
                    <div
                        class="absolute inset-y-0 left-0 rounded-full bg-white"
                        :class="activeVideoIndex === index ? 'w-full transition-all duration-[8000ms] ease-linear' : 'w-0 transition-none'"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
