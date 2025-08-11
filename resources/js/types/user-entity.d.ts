import { RoleEntity } from '@/types/entity/role-entity';

export interface UserEntity {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;

    role: RoleEntity
}
