INSERT INTO t_admin
(
    name,
    email,
    password,
    created_by,
    created_at,
    deleted_flg
)
VALUES
(
    'Admin',
    'admin@gmail.com',
    'Admin@123',
    'SYSTEM',
    CURRENT_TIMESTAMP,
    0
);