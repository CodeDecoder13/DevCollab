<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import {
    ArrowRight,
    CheckCircle2,
    Mail,
    MapPin,
    MessageSquare,
    Send,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref } from 'vue';
import MarketingLayout from '@/layouts/MarketingLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';

const visibleSections = ref<Set<string>>(new Set());
let observer: IntersectionObserver | null = null;

onMounted(() => {
    observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    visibleSections.value.add(entry.target.getAttribute('data-animate') || '');
                    observer?.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.1 },
    );
    document.querySelectorAll('[data-animate]').forEach((el) => observer?.observe(el));
});

onUnmounted(() => observer?.disconnect());

function isVisible(key: string) {
    return visibleSections.value.has(key);
}

const page = usePage();
const successMessage = computed(() => page.props.flash?.success as string | undefined);

const form = useForm({
    name: '',
    email: '',
    company: '',
    message: '',
});

function submit() {
    form.post('/contact', {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <Head title="Contact — DevCollab" />

    <MarketingLayout>
        <!-- Hero -->
        <section class="relative overflow-hidden pt-32 pb-20 sm:pt-40 sm:pb-28">
            <div class="absolute inset-0 -z-10">
                <div class="absolute left-1/2 top-0 h-[500px] w-[800px] -translate-x-1/2 rounded-full bg-gradient-to-b from-indigo-500/10 to-transparent blur-3xl" />
            </div>
            <div
                data-animate="hero"
                class="mx-auto max-w-3xl px-4 text-center transition-all duration-700 sm:px-6"
                :class="isVisible('hero') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
            >
                <div class="mb-6 inline-flex items-center gap-2 rounded-full bg-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-primary">
                    <MessageSquare class="h-3.5 w-3.5" />
                    Contact
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight sm:text-5xl lg:text-6xl" style="letter-spacing: -0.02em">
                    Get in
                    <span class="bg-gradient-to-r from-indigo-500 to-violet-500 bg-clip-text text-transparent">touch</span>
                </h1>
                <p class="mx-auto mt-6 max-w-2xl text-lg text-muted-foreground" style="line-height: 1.7">
                    Have a question, need a demo, or want to learn more? We'd love to hear from you.
                </p>
            </div>
        </section>

        <!-- Contact Form + Info -->
        <section class="pb-24 sm:pb-32">
            <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                <div
                    data-animate="contact-form"
                    class="grid gap-8 sm:gap-10 lg:gap-12 lg:grid-cols-5 transition-all duration-700"
                    :class="isVisible('contact-form') ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8'"
                >
                    <!-- Form -->
                    <div class="lg:col-span-3">
                        <div class="overflow-hidden rounded-2xl border border-border/50 bg-card p-6 shadow-sm sm:p-8">
                            <!-- Success Message -->
                            <div
                                v-if="successMessage"
                                class="mb-6 flex items-center gap-3 rounded-lg border border-emerald-500/20 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-500/10 dark:bg-emerald-950/20 dark:text-emerald-400"
                            >
                                <CheckCircle2 class="h-5 w-5 shrink-0" />
                                {{ successMessage }}
                            </div>

                            <form @submit.prevent="submit" class="space-y-6">
                                <div class="grid gap-6 sm:grid-cols-2">
                                    <div class="space-y-2">
                                        <Label for="name">Name</Label>
                                        <Input
                                            id="name"
                                            v-model="form.name"
                                            placeholder="Your name"
                                            required
                                        />
                                        <p v-if="form.errors.name" class="text-xs text-destructive">{{ form.errors.name }}</p>
                                    </div>
                                    <div class="space-y-2">
                                        <Label for="email">Email</Label>
                                        <Input
                                            id="email"
                                            v-model="form.email"
                                            type="email"
                                            placeholder="you@company.com"
                                            required
                                        />
                                        <p v-if="form.errors.email" class="text-xs text-destructive">{{ form.errors.email }}</p>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <Label for="company">Company <span class="text-muted-foreground">(optional)</span></Label>
                                    <Input
                                        id="company"
                                        v-model="form.company"
                                        placeholder="Your company"
                                    />
                                    <p v-if="form.errors.company" class="text-xs text-destructive">{{ form.errors.company }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="message">Message</Label>
                                    <Textarea
                                        id="message"
                                        v-model="form.message"
                                        placeholder="Tell us how we can help..."
                                        rows="5"
                                        required
                                    />
                                    <p v-if="form.errors.message" class="text-xs text-destructive">{{ form.errors.message }}</p>
                                </div>
                                <Button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="w-full bg-gradient-to-r from-indigo-600 to-violet-600 text-white shadow-md shadow-indigo-500/25 hover:shadow-lg hover:shadow-indigo-500/30 border-0 sm:w-auto"
                                >
                                    <Send class="mr-2 h-4 w-4" />
                                    {{ form.processing ? 'Sending...' : 'Send Message' }}
                                </Button>
                            </form>
                        </div>
                    </div>

                    <!-- Info Cards -->
                    <div class="space-y-4 sm:space-y-6 lg:col-span-2">
                        <div class="rounded-2xl border border-border/50 bg-card p-6 shadow-sm">
                            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-950/50">
                                <Mail class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                            </div>
                            <h3 class="font-semibold">Email Us</h3>
                            <p class="mt-1 text-sm text-muted-foreground">For general inquiries and support</p>
                            <p class="mt-2 text-sm font-medium text-primary">hello@devcollab.io</p>
                        </div>
                        <div class="rounded-2xl border border-border/50 bg-card p-6 shadow-sm">
                            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-violet-100 dark:bg-violet-950/50">
                                <MapPin class="h-5 w-5 text-violet-600 dark:text-violet-400" />
                            </div>
                            <h3 class="font-semibold">Office</h3>
                            <p class="mt-1 text-sm text-muted-foreground">Visit us at our headquarters</p>
                            <p class="mt-2 text-sm font-medium">San Francisco, CA</p>
                        </div>
                        <div class="rounded-2xl border border-border/50 bg-card p-6 shadow-sm">
                            <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 dark:bg-emerald-950/50">
                                <MessageSquare class="h-5 w-5 text-emerald-600 dark:text-emerald-400" />
                            </div>
                            <h3 class="font-semibold">Live Chat</h3>
                            <p class="mt-1 text-sm text-muted-foreground">Chat with our team in real-time</p>
                            <p class="mt-2 text-sm font-medium text-primary">Available Mon-Fri, 9am-6pm PST</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </MarketingLayout>
</template>
