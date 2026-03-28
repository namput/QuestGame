-- ============================================================
-- CodeQuest - Supabase Database Schema
-- ผจญภัยแดนโค้ด - ระบบสมาชิกและเก็บ Progress
-- ============================================================
-- วิธีใช้: Copy SQL นี้ไปรันใน Supabase Dashboard > SQL Editor
-- ============================================================

-- 1. ตาราง profiles (ข้อมูลสมาชิก)
CREATE TABLE IF NOT EXISTS profiles (
  id UUID REFERENCES auth.users(id) ON DELETE CASCADE PRIMARY KEY,
  display_name TEXT NOT NULL DEFAULT '',
  avatar_url TEXT DEFAULT '',
  total_xp INTEGER DEFAULT 0,
  created_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- 2. ตาราง game_progress (เก็บความก้าวหน้าแต่ละเกม)
CREATE TABLE IF NOT EXISTS game_progress (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  user_id UUID REFERENCES profiles(id) ON DELETE CASCADE NOT NULL,
  game_key TEXT NOT NULL,           -- 'python', 'javascript', 'htmlcss', 'sql'
  current_level INTEGER DEFAULT 1,
  max_level_reached INTEGER DEFAULT 1,
  total_xp INTEGER DEFAULT 0,
  completed_levels JSONB DEFAULT '[]'::jsonb,  -- [1,2,3,...] ด่านที่ผ่านแล้ว
  level_scores JSONB DEFAULT '{}'::jsonb,       -- {"1": {"xp": 50, "hints_used": 0}, ...}
  started_at TIMESTAMPTZ DEFAULT NOW(),
  updated_at TIMESTAMPTZ DEFAULT NOW(),
  UNIQUE(user_id, game_key)
);

-- 3. ตาราง certificates (ใบรับรอง)
CREATE TABLE IF NOT EXISTS certificates (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  user_id UUID REFERENCES profiles(id) ON DELETE CASCADE NOT NULL,
  game_key TEXT NOT NULL,
  cert_type TEXT NOT NULL,           -- 'bronze', 'silver', 'gold', 'platinum'
  cert_name TEXT NOT NULL,
  issued_at TIMESTAMPTZ DEFAULT NOW(),
  cert_code TEXT UNIQUE NOT NULL     -- รหัสใบรับรองไม่ซ้ำ
);

-- 4. ตาราง achievements (เหรียญรางวัล)
CREATE TABLE IF NOT EXISTS achievements (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  user_id UUID REFERENCES profiles(id) ON DELETE CASCADE NOT NULL,
  achievement_key TEXT NOT NULL,     -- 'first_level', 'speed_run', 'no_hints', etc.
  game_key TEXT,                     -- null = achievement ทั่วไป
  unlocked_at TIMESTAMPTZ DEFAULT NOW(),
  UNIQUE(user_id, achievement_key)
);

-- ============================================================
-- Row Level Security (RLS) - ความปลอดภัย
-- ============================================================

ALTER TABLE profiles ENABLE ROW LEVEL SECURITY;
ALTER TABLE game_progress ENABLE ROW LEVEL SECURITY;
ALTER TABLE certificates ENABLE ROW LEVEL SECURITY;
ALTER TABLE achievements ENABLE ROW LEVEL SECURITY;

-- profiles: อ่านได้ทุกคน, แก้ได้เฉพาะเจ้าของ
CREATE POLICY "Public profiles are viewable by everyone"
  ON profiles FOR SELECT USING (true);

CREATE POLICY "Users can update own profile"
  ON profiles FOR UPDATE USING (auth.uid() = id);

CREATE POLICY "Users can insert own profile"
  ON profiles FOR INSERT WITH CHECK (auth.uid() = id);

-- game_progress: เข้าถึงได้เฉพาะเจ้าของ
CREATE POLICY "Users can view own progress"
  ON game_progress FOR SELECT USING (auth.uid() = user_id);

CREATE POLICY "Users can insert own progress"
  ON game_progress FOR INSERT WITH CHECK (auth.uid() = user_id);

CREATE POLICY "Users can update own progress"
  ON game_progress FOR UPDATE USING (auth.uid() = user_id);

-- certificates: อ่านได้ทุกคน (เพื่อ verify)
CREATE POLICY "Certificates are viewable by everyone"
  ON certificates FOR SELECT USING (true);

CREATE POLICY "Users can insert own certificates"
  ON certificates FOR INSERT WITH CHECK (auth.uid() = user_id);

-- achievements: อ่านได้ทุกคน
CREATE POLICY "Achievements are viewable by everyone"
  ON achievements FOR SELECT USING (true);

CREATE POLICY "Users can insert own achievements"
  ON achievements FOR INSERT WITH CHECK (auth.uid() = user_id);

-- ============================================================
-- Functions
-- ============================================================

-- Auto-create profile เมื่อ user สมัครใหม่
CREATE OR REPLACE FUNCTION public.handle_new_user()
RETURNS TRIGGER AS $$
BEGIN
  INSERT INTO public.profiles (id, display_name, avatar_url)
  VALUES (
    NEW.id,
    COALESCE(NEW.raw_user_meta_data->>'display_name', NEW.raw_user_meta_data->>'name', split_part(NEW.email, '@', 1)),
    COALESCE(NEW.raw_user_meta_data->>'avatar_url', '')
  );
  RETURN NEW;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;

-- Trigger สร้าง profile อัตโนมัติ
DROP TRIGGER IF EXISTS on_auth_user_created ON auth.users;
CREATE TRIGGER on_auth_user_created
  AFTER INSERT ON auth.users
  FOR EACH ROW EXECUTE FUNCTION public.handle_new_user();

-- Function อัพเดท updated_at อัตโนมัติ
CREATE OR REPLACE FUNCTION update_updated_at()
RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = NOW();
  RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER update_profiles_updated_at
  BEFORE UPDATE ON profiles
  FOR EACH ROW EXECUTE FUNCTION update_updated_at();

CREATE TRIGGER update_progress_updated_at
  BEFORE UPDATE ON game_progress
  FOR EACH ROW EXECUTE FUNCTION update_updated_at();

-- Function คำนวณ total XP ของ user
CREATE OR REPLACE FUNCTION recalc_total_xp(uid UUID)
RETURNS VOID AS $$
BEGIN
  UPDATE profiles
  SET total_xp = COALESCE((
    SELECT SUM(total_xp) FROM game_progress WHERE user_id = uid
  ), 0)
  WHERE id = uid;
END;
$$ LANGUAGE plpgsql SECURITY DEFINER;
