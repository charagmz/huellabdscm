--DROP TABLE trace_struct;
--DROP TABLE trace_struct_det;
--DROP TABLE trace_struct_conf;

CREATE TABLE trace_struct (id SERIAL, date timestamp, trace_type varchar(2), gbl_entity_id int, PRIMARY KEY(id));
CREATE TABLE trace_struct_det (id SERIAL, trace_struct_id BIGINT, table_name varchar(100), sha1_info varchar(100), struct_info text, status varchar(1), PRIMARY KEY(id));
CREATE TABLE trace_struct_conf (id SERIAL, trace_struct_id BIGINT, var_name varchar(100), sha1_info varchar(100), struct_info text, status varchar(1), PRIMARY KEY(id));
ALTER TABLE trace_struct_det ADD CONSTRAINT trace_struct_det_trace_struct_id_trace_struct_id FOREIGN KEY (trace_struct_id) REFERENCES trace_struct(id) ON UPDATE CASCADE ON DELETE RESTRICT NOT DEFERRABLE INITIALLY IMMEDIATE;
ALTER TABLE trace_struct_conf ADD CONSTRAINT trace_struct_conf_trace_struct_id_trace_struct_id FOREIGN KEY (trace_struct_id) REFERENCES trace_struct(id) ON UPDATE CASCADE ON DELETE RESTRICT NOT DEFERRABLE INITIALLY IMMEDIATE;

REVOKE ALL ON TABLE trace_struct FROM PUBLIC, "phs-adm";
GRANT ALL ON TABLE trace_struct TO "phs-adm", "phs-user", "phs-soap";
REVOKE ALL ON SEQUENCE trace_struct_id_seq FROM PUBLIC, "phs-adm";
GRANT ALL ON SEQUENCE trace_struct_id_seq TO "phs-adm", "phs-user", "phs-soap";
REVOKE ALL ON TABLE trace_struct_det FROM PUBLIC, "phs-adm";
GRANT ALL ON TABLE trace_struct_det TO "phs-adm", "phs-user", "phs-soap";
REVOKE ALL ON SEQUENCE trace_struct_det_id_seq FROM PUBLIC, "phs-adm";
GRANT ALL ON SEQUENCE trace_struct_det_id_seq TO "phs-adm", "phs-user", "phs-soap";
REVOKE ALL ON TABLE trace_struct_conf FROM PUBLIC, "phs-adm";
GRANT ALL ON TABLE trace_struct_conf TO "phs-adm", "phs-user", "phs-soap";
REVOKE ALL ON SEQUENCE trace_struct_conf_id_seq FROM PUBLIC, "phs-adm";
GRANT ALL ON SEQUENCE trace_struct_conf_id_seq TO "phs-adm", "phs-user", "phs-soap";
