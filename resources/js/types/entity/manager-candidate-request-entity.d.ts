export interface ManagerCandidateRequestEntity {
    id: number;
    manager: {
        id: number;
        name: string;
    }
    position: {
        id: number;
        name: string;
    }
    status: string;
    level: string;
    note: string;
    requested_count: number;
    fulfilled_count: number;
    date_requested: string;
    created_at: string;
    updated_at: string;
}
