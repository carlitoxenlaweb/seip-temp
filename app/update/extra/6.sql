CREATE TABLE seip_report_product_unrealized_production_day_cause_fail (id INT AUTO_INCREMENT NOT NULL, fail_id INT NOT NULL, mount DOUBLE PRECISION NOT NULL, INDEX IDX_60CC59D3CA703BFB (fail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE seip_report_product_unrealized_production_day (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE unrealizedproductionday_internalcauses (unrealizedproductionday_id INT NOT NULL, causefail_id INT NOT NULL, INDEX IDX_2CABC09388952D93 (unrealizedproductionday_id), INDEX IDX_2CABC093A31ACAF9 (causefail_id), PRIMARY KEY(unrealizedproductionday_id, causefail_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE unrealizedproductionday_externalcauses (unrealizedproductionday_id INT NOT NULL, causefail_id INT NOT NULL, INDEX IDX_12BC28B988952D93 (unrealizedproductionday_id), INDEX IDX_12BC28B9A31ACAF9 (causefail_id), PRIMARY KEY(unrealizedproductionday_id, causefail_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE unrealizedproductionday_internalcauses_mp (unrealizedproductionday_id INT NOT NULL, rawmaterialrequired_id INT NOT NULL, INDEX IDX_6D5C7FE288952D93 (unrealizedproductionday_id), INDEX IDX_6D5C7FE27B662C73 (rawmaterialrequired_id), PRIMARY KEY(unrealizedproductionday_id, rawmaterialrequired_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE unrealizedproductionday_externalcauses_mp (unrealizedproductionday_id INT NOT NULL, rawmaterialrequired_id INT NOT NULL, INDEX IDX_4DF240C988952D93 (unrealizedproductionday_id), INDEX IDX_4DF240C97B662C73 (rawmaterialrequired_id), PRIMARY KEY(unrealizedproductionday_id, rawmaterialrequired_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
CREATE TABLE seip_report_product_unrealized_production_raw_material_required (id INT AUTO_INCREMENT NOT NULL, required_amount DOUBLE PRECISION NOT NULL, amount_not_available DOUBLE PRECISION NOT NULL, rawMaterial_id INT NOT NULL, unitMeasure_id INT NOT NULL, INDEX IDX_C57DE3C1571EF7DB (rawMaterial_id), INDEX IDX_C57DE3C14116134F (unitMeasure_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB;
ALTER TABLE seip_report_product_unrealized_production_day_cause_fail ADD CONSTRAINT FK_60CC59D3CA703BFB FOREIGN KEY (fail_id) REFERENCES seip_cei_fail (id);
ALTER TABLE unrealizedproductionday_internalcauses ADD CONSTRAINT FK_2CABC09388952D93 FOREIGN KEY (unrealizedproductionday_id) REFERENCES seip_report_product_unrealized_production_day (id) ON DELETE CASCADE;
ALTER TABLE unrealizedproductionday_internalcauses ADD CONSTRAINT FK_2CABC093A31ACAF9 FOREIGN KEY (causefail_id) REFERENCES seip_report_product_unrealized_production_day_cause_fail (id) ON DELETE CASCADE;
ALTER TABLE unrealizedproductionday_externalcauses ADD CONSTRAINT FK_12BC28B988952D93 FOREIGN KEY (unrealizedproductionday_id) REFERENCES seip_report_product_unrealized_production_day (id) ON DELETE CASCADE;
ALTER TABLE unrealizedproductionday_externalcauses ADD CONSTRAINT FK_12BC28B9A31ACAF9 FOREIGN KEY (causefail_id) REFERENCES seip_report_product_unrealized_production_day_cause_fail (id) ON DELETE CASCADE;
ALTER TABLE unrealizedproductionday_internalcauses_mp ADD CONSTRAINT FK_6D5C7FE288952D93 FOREIGN KEY (unrealizedproductionday_id) REFERENCES seip_report_product_unrealized_production_day (id) ON DELETE CASCADE;
ALTER TABLE unrealizedproductionday_internalcauses_mp ADD CONSTRAINT FK_6D5C7FE27B662C73 FOREIGN KEY (rawmaterialrequired_id) REFERENCES seip_report_product_unrealized_production_raw_material_required (id) ON DELETE CASCADE;
ALTER TABLE unrealizedproductionday_externalcauses_mp ADD CONSTRAINT FK_4DF240C988952D93 FOREIGN KEY (unrealizedproductionday_id) REFERENCES seip_report_product_unrealized_production_day (id) ON DELETE CASCADE;
ALTER TABLE unrealizedproductionday_externalcauses_mp ADD CONSTRAINT FK_4DF240C97B662C73 FOREIGN KEY (rawmaterialrequired_id) REFERENCES seip_report_product_unrealized_production_raw_material_required (id) ON DELETE CASCADE;
ALTER TABLE seip_report_product_unrealized_production_raw_material_required ADD CONSTRAINT FK_C57DE3C1571EF7DB FOREIGN KEY (rawMaterial_id) REFERENCES seip_cei_Product (id);
ALTER TABLE seip_report_product_unrealized_production_raw_material_required ADD CONSTRAINT FK_C57DE3C14116134F FOREIGN KEY (unitMeasure_id) REFERENCES seip_cei_unit_measure (id);
ALTER TABLE seip_report_product_report_product_detail_daily_month ADD day1_observation LONGTEXT DEFAULT NULL, ADD day2_observation LONGTEXT DEFAULT NULL, ADD day3_observation LONGTEXT DEFAULT NULL, ADD day4_observation LONGTEXT DEFAULT NULL, ADD day5_observation LONGTEXT DEFAULT NULL, ADD day6_observation LONGTEXT DEFAULT NULL, ADD day7_observation LONGTEXT DEFAULT NULL, ADD day8_observation LONGTEXT DEFAULT NULL, ADD day9_observation LONGTEXT DEFAULT NULL, ADD day10_observation LONGTEXT DEFAULT NULL, ADD day11_observation LONGTEXT DEFAULT NULL, ADD day12_observation LONGTEXT DEFAULT NULL, ADD day13_observation LONGTEXT DEFAULT NULL, ADD day14_observation LONGTEXT DEFAULT NULL, ADD day15_observation LONGTEXT DEFAULT NULL, ADD day16_observation LONGTEXT DEFAULT NULL, ADD day17_observation LONGTEXT DEFAULT NULL, ADD day18_observation LONGTEXT DEFAULT NULL, ADD day19_observation LONGTEXT DEFAULT NULL, ADD day20_observation LONGTEXT DEFAULT NULL, ADD day21_observation LONGTEXT DEFAULT NULL, ADD day22_observation LONGTEXT DEFAULT NULL, ADD day23_observation LONGTEXT DEFAULT NULL, ADD day24_observation LONGTEXT DEFAULT NULL, ADD day25_observation LONGTEXT DEFAULT NULL, ADD day26_observation LONGTEXT DEFAULT NULL, ADD day27_observation LONGTEXT DEFAULT NULL, ADD day28_observation LONGTEXT DEFAULT NULL, ADD day29_observation LONGTEXT DEFAULT NULL, ADD day30_observation LONGTEXT DEFAULT NULL, ADD day31_observation LONGTEXT DEFAULT NULL;
ALTER TABLE seip_report_product_unrealized_production ADD day1Details_id INT DEFAULT NULL, ADD day2Details_id INT DEFAULT NULL, ADD day3Details_id INT DEFAULT NULL, ADD day4Details_id INT DEFAULT NULL, ADD day5Details_id INT DEFAULT NULL, ADD day6Details_id INT DEFAULT NULL, ADD day7Details_id INT DEFAULT NULL, ADD day8Details_id INT DEFAULT NULL, ADD day9Details_id INT DEFAULT NULL, ADD day10Details_id INT DEFAULT NULL, ADD day11Details_id INT DEFAULT NULL, ADD day12Details_id INT DEFAULT NULL, ADD day13Details_id INT DEFAULT NULL, ADD day14Details_id INT DEFAULT NULL, ADD day15Details_id INT DEFAULT NULL, ADD day16Details_id INT DEFAULT NULL, ADD day17Details_id INT DEFAULT NULL, ADD day18Details_id INT DEFAULT NULL, ADD day19Details_id INT DEFAULT NULL, ADD day20Details_id INT DEFAULT NULL, ADD day21Details_id INT DEFAULT NULL, ADD day22Details_id INT DEFAULT NULL, ADD day23Details_id INT DEFAULT NULL, ADD day24Details_id INT DEFAULT NULL, ADD day25Details_id INT DEFAULT NULL, ADD day26Details_id INT DEFAULT NULL, ADD day27Details_id INT DEFAULT NULL, ADD day28Details_id INT DEFAULT NULL, ADD day29Details_id INT DEFAULT NULL, ADD day30Details_id INT DEFAULT NULL, ADD day31Details_id INT DEFAULT NULL;
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F4D0653913 FOREIGN KEY (day1Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F449875F12 FOREIGN KEY (day2Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F4880980D2 FOREIGN KEY (day3Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F4A1329551 FOREIGN KEY (day4Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F460BC4A91 FOREIGN KEY (day5Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F4F95E2C90 FOREIGN KEY (day6Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F438D0F350 FOREIGN KEY (day7Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F4AB280796 FOREIGN KEY (day8Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F46AA6D856 FOREIGN KEY (day9Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F4830966A7 FOREIGN KEY (day10Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F44287B967 FOREIGN KEY (day11Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F4DB65DF66 FOREIGN KEY (day12Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F41AEB00A6 FOREIGN KEY (day13Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F433D01525 FOREIGN KEY (day14Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F4F25ECAE5 FOREIGN KEY (day15Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F46BBCACE4 FOREIGN KEY (day16Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F4AA327324 FOREIGN KEY (day17Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F439CA87E2 FOREIGN KEY (day18Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F4F8445822 FOREIGN KEY (day19Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F4F497B457 FOREIGN KEY (day20Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F435196B97 FOREIGN KEY (day21Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F4ACFB0D96 FOREIGN KEY (day22Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F46D75D256 FOREIGN KEY (day23Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F4444EC7D5 FOREIGN KEY (day24Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F485C01815 FOREIGN KEY (day25Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F41C227E14 FOREIGN KEY (day26Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F4DDACA1D4 FOREIGN KEY (day27Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F44E545512 FOREIGN KEY (day28Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F48FDA8AD2 FOREIGN KEY (day29Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F46F32F838 FOREIGN KEY (day30Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
ALTER TABLE seip_report_product_unrealized_production ADD CONSTRAINT FK_BD9726F4AEBC27F8 FOREIGN KEY (day31Details_id) REFERENCES seip_report_product_unrealized_production_day (id);
CREATE UNIQUE INDEX UNIQ_BD9726F4D0653913 ON seip_report_product_unrealized_production (day1Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F449875F12 ON seip_report_product_unrealized_production (day2Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F4880980D2 ON seip_report_product_unrealized_production (day3Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F4A1329551 ON seip_report_product_unrealized_production (day4Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F460BC4A91 ON seip_report_product_unrealized_production (day5Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F4F95E2C90 ON seip_report_product_unrealized_production (day6Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F438D0F350 ON seip_report_product_unrealized_production (day7Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F4AB280796 ON seip_report_product_unrealized_production (day8Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F46AA6D856 ON seip_report_product_unrealized_production (day9Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F4830966A7 ON seip_report_product_unrealized_production (day10Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F44287B967 ON seip_report_product_unrealized_production (day11Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F4DB65DF66 ON seip_report_product_unrealized_production (day12Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F41AEB00A6 ON seip_report_product_unrealized_production (day13Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F433D01525 ON seip_report_product_unrealized_production (day14Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F4F25ECAE5 ON seip_report_product_unrealized_production (day15Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F46BBCACE4 ON seip_report_product_unrealized_production (day16Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F4AA327324 ON seip_report_product_unrealized_production (day17Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F439CA87E2 ON seip_report_product_unrealized_production (day18Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F4F8445822 ON seip_report_product_unrealized_production (day19Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F4F497B457 ON seip_report_product_unrealized_production (day20Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F435196B97 ON seip_report_product_unrealized_production (day21Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F4ACFB0D96 ON seip_report_product_unrealized_production (day22Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F46D75D256 ON seip_report_product_unrealized_production (day23Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F4444EC7D5 ON seip_report_product_unrealized_production (day24Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F485C01815 ON seip_report_product_unrealized_production (day25Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F41C227E14 ON seip_report_product_unrealized_production (day26Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F4DDACA1D4 ON seip_report_product_unrealized_production (day27Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F44E545512 ON seip_report_product_unrealized_production (day28Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F48FDA8AD2 ON seip_report_product_unrealized_production (day29Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F46F32F838 ON seip_report_product_unrealized_production (day30Details_id);
CREATE UNIQUE INDEX UNIQ_BD9726F4AEBC27F8 ON seip_report_product_unrealized_production (day31Details_id);