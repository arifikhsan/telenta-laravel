import { UserEntity } from '@/types/entity/user-entity';

export interface ManagerEntity extends UserEntity {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;

    role: RoleEntity;

    client: {
        id: number;
        name: string;
    };
}
