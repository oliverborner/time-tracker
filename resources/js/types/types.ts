export interface User {
    id: number;
    name: string;
    email: string;
    is_admin: boolean;
    role: string;
}

export interface Project {
    id: number;
    name: string;
    description?: string;
}

export interface TimeEntry {
    id: number;
    project_id: number;
    user_id: number;
    day: string;
    minutes: number;
    note?: string;
    locked: boolean;
}

export interface MonthClosure {
    id: number;
    year: number;
    month: number;
    summary: Record<string, number>; 
}