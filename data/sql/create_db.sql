CREATE TABLE IF NOT EXISTS employee (
  "id" SERIAL,
  first_name VARCHAR(40) NOT NULL,
  last_name VARCHAR(40) NOT NULL,
  fathers_name VARCHAR(40),
  phone_number BIGINT NOT NULL,
  tariff NUMERIC(8, 2),
  id_sto INTEGER NOT NULL,
  id_branch INTEGER NOT NULL,
  id_position INTEGER NOT NULL,
  id_role INTEGER NOT NULL,
  login VARCHAR(20) NOT NULL UNIQUE,
  password VARCHAR(32) NOT NULL
);

CREATE TABLE IF NOT EXISTS branch (
  "id" SERIAL,
  department_name VARCHAR(255) NOT NULL UNIQUE,
  description TEXT
);

CREATE TABLE IF NOT EXISTS "position" (
  "id" SERIAL,
  position_name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS "role" (
  "id" SERIAL,
  role_name VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS "order" (
  "id" SERIAL,
  id_client INTEGER NOT NULL,
  create_date DATE NOT NULL,
  completed BOOLEAN NOT NULL,
  payment BOOLEAN NOT NULL,
  discount SMALLINT,
  id_car INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS task (
  "id" SERIAL,
  id_order INTEGER NOT NULL,
  id_service INTEGER NOT NULL,
  id_employee INTEGER NOT NULL,
  is_done BOOLEAN NOT NULL
);

CREATE TABLE IF NOT EXISTS sto (
  "id" SERIAL,
  id_address INTEGER NOT NULL,
  sto_name VARCHAR(255) NOT NULL UNIQUE,
  phone_number BIGINT UNIQUE,
  schedule JSONB
);

CREATE TABLE IF NOT EXISTS client (
  "id" SERIAL,
  first_name VARCHAR(40) NOT NULL,
  last_name VARCHAR(40) NOT NULL,
  fathers_name VARCHAR(40),
  phone_number BIGINT UNIQUE,
  discount SMALLINT
);

CREATE TABLE IF NOT EXISTS car (
  "id" SERIAL,
  make VARCHAR(40) NOT NULL,
  model VARCHAR(40) NOT NULL,
  car_year VARCHAR(4),
  license_plate VARCHAR(10) NOT NULL UNIQUE,
  description TEXT
);

CREATE TABLE IF NOT EXISTS service (
  "id" SERIAL,
  service_name VARCHAR(40) NOT NULL UNIQUE,
  description TEXT,
  price NUMERIC(6, 2)
);

CREATE TABLE IF NOT EXISTS detail (
  "id" SERIAL,
  detail_name VARCHAR(40) NOT NULL UNIQUE,
  description TEXT,
  article BIGINT NOT NULL UNIQUE,
  price NUMERIC(6, 2)
);

CREATE TABLE IF NOT EXISTS sto_detail (
  "id" SERIAL,
  id_detail INTEGER NOT NULL,
  id_sto INTEGER NOT NULL,
  quantity INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS address (
  "id" SERIAL,
  country VARCHAR(80) NOT NULL,
  region VARCHAR(80),
  city VARCHAR(80) NOT NULL,
  district VARCHAR(80),
  street VARCHAR(80) NOT NULL,
  house VARCHAR(5) NOT NULL,
  office VARCHAR(3)
);

CREATE TABLE IF NOT EXISTS schedule (
  "id" SERIAL,
  schedule_year SMALLINT,
  schedule_month SMALLINT,
  schedule JSONB
);

CREATE TABLE IF NOT EXISTS schedule_employee (
    "id" SERIAL,
    id_employee INTEGER NOT NULL,
    id_schedule INTEGER NOT NULL
);

CREATE TABLE IF NOT EXISTS "session" (
  "id" SERIAL,
  session_create_date TIMESTAMP WITH TIME ZONE,
  session_last_date TIMESTAMP WITH TIME ZONE,
  id_employee INTEGER,
  session_key VARCHAR(128) UNIQUE,
  data TEXT
);





ALTER TABLE employee
ADD PRIMARY KEY ("id");

ALTER TABLE branch
ADD PRIMARY KEY ("id");

ALTER TABLE "position"
ADD PRIMARY KEY ("id");

ALTER TABLE "role"
ADD PRIMARY KEY ("id");

ALTER TABLE "order"
ADD PRIMARY KEY ("id");

ALTER TABLE task
ADD PRIMARY KEY ("id");

ALTER TABLE sto
ADD PRIMARY KEY ("id");

ALTER TABLE client
ADD PRIMARY KEY ("id");

ALTER TABLE car
ADD PRIMARY KEY ("id");

ALTER TABLE service
ADD PRIMARY KEY ("id");

ALTER TABLE detail
ADD PRIMARY KEY ("id");

ALTER TABLE sto_detail
ADD PRIMARY KEY ("id");

ALTER TABLE address
ADD PRIMARY KEY ("id");

ALTER TABLE schedule
ADD PRIMARY KEY ("id");

ALTER TABLE schedule_employee
ADD PRIMARY KEY ("id");

ALTER TABLE "session"
ADD PRIMARY KEY ("id");





ALTER TABLE employee
ADD FOREIGN KEY (id_sto)      REFERENCES sto("id") ON DELETE CASCADE,
ADD FOREIGN KEY (id_branch)   REFERENCES branch("id") ON DELETE CASCADE,
ADD FOREIGN KEY (id_position) REFERENCES "position"("id") ON DELETE CASCADE,
ADD FOREIGN KEY (id_role)     REFERENCES "role"("id") ON DELETE CASCADE;

ALTER TABLE "order"
ADD FOREIGN KEY (id_client) REFERENCES client("id") ON DELETE SET NULL,
ADD FOREIGN KEY (id_car)      REFERENCES car("id");

ALTER TABLE task
ADD FOREIGN KEY (id_order) REFERENCES "order"("id") ON DELETE CASCADE,
ADD FOREIGN KEY (id_service) REFERENCES service("id") ON DELETE SET NULL,
ADD FOREIGN KEY (id_employee) REFERENCES employee("id") ON DELETE SET NULL,
ADD CONSTRAINT task_order_service_employee UNIQUE (id_order, id_service, id_employee);

ALTER TABLE sto
ADD FOREIGN KEY (id_address) REFERENCES address("id");

ALTER TABLE sto_detail
ADD FOREIGN KEY (id_detail) REFERENCES detail("id"),
ADD FOREIGN KEY (id_sto) REFERENCES sto("id");

ALTER TABLE "session"
ADD FOREIGN KEY (id_employee) REFERENCES employee("id") ON DELETE CASCADE;

ALTER TABLE schedule_employee
ADD FOREIGN KEY (id_employee) REFERENCES employee("id") ON DELETE CASCADE,
ADD FOREIGN KEY (id_schedule) REFERENCES schedule("id") ON DELETE SET NULL;