export * from './auth';
export * from './models';
export * from './navigation';
export * from './ui';

import type { Auth } from './auth';

export type Flash = {
    success?: string;
    error?: string;
};

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    auth: Auth;
    sidebarOpen: boolean;
    flash: Flash;
    [key: string]: unknown;
};
