DROP TABLE IF EXISTS AnswerPolicies;
DROP TABLE IF EXISTS AnswerResults;
DROP TABLE IF EXISTS Exams;
DROP TABLE IF EXISTS FacultyMemberAllocations;
DROP TABLE IF EXISTS FacultyMembers;
DROP TABLE IF EXISTS FeedbackSessions;
DROP TABLE IF EXISTS FinishedGameQueue;
DROP TABLE IF EXISTS Membership_Roles;
DROP TABLE IF EXISTS Membership_Students;
DROP TABLE IF EXISTS Membership_Users;
DROP TABLE IF EXISTS Membership_UsersInRoles;
DROP TABLE IF EXISTS PreExamRevisions;
DROP TABLE IF EXISTS QuizPolicies;
DROP TABLE IF EXISTS QuizPolicyContent;
DROP TABLE IF EXISTS QuizPolicyScope;
DROP TABLE IF EXISTS QuizSessionResults;

TRUNCATE TABLE PlanningWeekPreResults;
TRUNCATE TABLE PlanningWeekExerciseResults;
TRUNCATE TABLE PlanningWeekPostResults;
TRUNCATE TABLE PlanningBlockPreResults;
TRUNCATE TABLE PlanningBlockPostResults;

TRUNCATE TABLE BlockExamGameAttendances;
TRUNCATE TABLE WeekExamGameAttendances;
TRUNCATE TABLE BlockExamStages;
TRUNCATE TABLE WeekExamStages;

TRUNCATE TABLE PrivatePlanningWeeks;
TRUNCATE TABLE PrivatePlanningBlocks;
TRUNCATE TABLE PlanningWeeks;
TRUNCATE TABLE PlanningWeekContent;
TRUNCATE TABLE PlanningBlocks;
TRUNCATE TABLE PlanningBlockContent;
TRUNCATE TABLE PlanningWeekExercises;
TRUNCATE TABLE PlanningWeekExerciseContent;

TRUNCATE TABLE Students;
TRUNCATE TABLE StudentLogins;

TRUNCATE TABLE Heartbeats;
TRUNCATE TABLE EntityUpdates;
TRUNCATE TABLE DeviceInstalledApps;
TRUNCATE TABLE DeviceCheckIns;
TRUNCATE TABLE DbUpdates;
TRUNCATE TABLE CloudSyncLog;
TRUNCATE TABLE RequestLogs;