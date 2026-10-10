import assert from "node:assert/strict";
import { existsSync, readFileSync } from "node:fs";
import { join } from "node:path";

const root = process.cwd();
const files = [
  "index.php",
  "main_menu.php",
  "catalog.php",
  "activities.php",
  "community.php",
  "community_detail.php",
  "profile.php",
  "update_profile.php",
  "registration.php",
  "process_registration.php",
  "activity_reg.php",
  "login.php",
  "about.php",
];

for (const file of files) {
  assert.ok(existsSync(join(root, file)), `${file} should exist in the project root`);
}

for (const dir of ["style", "img", "data"]) {
  assert.ok(existsSync(join(root, dir)), `${dir}/ should exist`);
}

const mainMenu = readFileSync(join(root, "main_menu.php"), "utf8");
for (const target of ["catalog.php", "activities.php", "community.php", "smart_feature.php"]) {
  assert.ok(mainMenu.includes(target), `main_menu.php should link to ${target}`);
}

const registration = readFileSync(join(root, "registration.php"), "utf8");
assert.match(registration, /method=["']post["']/i, "registration form should use POST");
assert.match(registration, /name=["']email["']/i, "registration form should include email");
assert.doesNotMatch(registration, /type=["']email["']/i, "registration email should be text input");

const processRegistration = readFileSync(join(root, "process_registration.php"), "utf8");
assert.match(processRegistration, /preg_match/, "process_registration.php should validate fields");
assert.match(processRegistration, /PASSWORD_PATTERN/, "process_registration.php should enforce password complexity");
assert.match(processRegistration, /Email:/, "process_registration.php should save labelled email field");
assert.match(processRegistration, /\|/, "process_registration.php should use pipe-delimited records");

const activity = readFileSync(join(root, "activity_reg.php"), "utf8");
for (const field of ["first_name", "last_name", "contact", "email", "activity_datetime", "activity_title"]) {
  assert.ok(activity.includes(`name="${field}"`) || activity.includes(`name='${field}'`), `activity_reg.php should include ${field}`);
}

const about = readFileSync(join(root, "about.php"), "utf8");
assert.match(about, /phpversion\(\)/, "about.php should output PHP version with phpversion()");
