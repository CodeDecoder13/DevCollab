import type { User } from './auth';

export type ProjectStatus = 'active' | 'on_hold' | 'completed';
export type ProjectMemberRole = 'owner' | 'manager' | 'member';
export type TaskStatus = 'todo' | 'in_progress' | 'completed';
export type TaskPriority = 'low' | 'medium' | 'high' | 'critical';

export type ProjectMember = User & {
    pivot: {
        role: ProjectMemberRole;
    };
};

export type Label = {
    id: number;
    name: string;
    color: string;
    project_id: number;
    created_at: string;
    updated_at: string;
};

export type Project = {
    id: number;
    name: string;
    description: string | null;
    status: ProjectStatus;
    owner_id: number;
    archived_at: string | null;
    created_at: string;
    updated_at: string;
    owner?: User;
    members?: ProjectMember[];
    tasks?: Task[];
    labels?: Label[];
    tasks_count?: number;
    completed_tasks_count?: number;
};

export type Task = {
    id: number;
    title: string;
    description: string | null;
    status: TaskStatus;
    priority: TaskPriority;
    project_id: number;
    assignee_id: number | null;
    creator_id: number;
    due_date: string | null;
    position: number;
    parent_task_id: number | null;
    created_at: string;
    updated_at: string;
    project?: Project;
    assignee?: User | null;
    creator?: User;
    comments?: Comment[];
    attachments?: Attachment[];
    subtasks?: Task[];
    labels?: Label[];
    comments_count?: number;
    attachments_count?: number;
    subtasks_count?: number;
    completed_subtasks_count?: number;
};

export type Comment = {
    id: number;
    body: string;
    user_id: number;
    task_id: number;
    created_at: string;
    updated_at: string;
    user?: User;
};

export type ActivityLog = {
    id: number;
    subject_type: string;
    subject_id: number;
    description: string;
    event: string | null;
    user_id: number | null;
    properties: Record<string, unknown> | null;
    created_at: string;
    updated_at: string;
    user?: User | null;
};

export type Attachment = {
    id: number;
    filename: string;
    path: string;
    mime_type: string;
    size: number;
    task_id: number;
    user_id: number;
    created_at: string;
    updated_at: string;
    user?: User;
};

export type AppNotification = {
    id: string;
    type: string;
    data: {
        task_id: number;
        task_title: string;
        project_id: number;
        project_name: string;
        message: string;
        commenter_name?: string;
    };
    read_at: string | null;
    created_at: string;
};
