CREATE TABLE IF NOT EXISTS public."images" (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL REFERENCES public."users"(id) ON DELETE CASCADE,
    filename VARCHAR(255) NOT NULL,
    filepath VARCHAR(500) NOT NULL,
    mimetype VARCHAR(50) NOT NULL,
    size_bytes INT,
    type VARCHAR(50),
    created_at TIMESTAMPTZ NOT NULL DEFAULT now()
);
ALTER TABLE public.users
ADD COLUMN IF NOT EXISTS profile_image_id INT REFERENCES public.images(id) ON DELETE SET NULL;