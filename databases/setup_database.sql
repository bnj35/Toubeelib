-- Create databases and users
CREATE DATABASE auth_db;
CREATE USER auth_user WITH ENCRYPTED PASSWORD 'auth_password';
GRANT ALL PRIVILEGES ON DATABASE auth_db TO auth_user;

CREATE DATABASE rdv_db;
CREATE USER rdv_user WITH ENCRYPTED PASSWORD 'rdv_password';
GRANT ALL PRIVILEGES ON DATABASE rdv_db TO rdv_user;

CREATE DATABASE patient_db;
CREATE USER patient_user WITH ENCRYPTED PASSWORD 'patient_password';
GRANT ALL PRIVILEGES ON DATABASE patient_db TO patient_user;

CREATE DATABASE praticien_db;
CREATE USER praticien_user WITH ENCRYPTED PASSWORD 'praticien_password';
GRANT ALL PRIVILEGES ON DATABASE praticien_db TO praticien_user;

\c auth_db
DROP TABLE IF EXISTS "users";
CREATE TABLE "public"."users" (
    "id" uuid NOT NULL,
    "email" character varying(128) NOT NULL,
    "password" character varying(256) NOT NULL,
    "role" smallint DEFAULT '0' NOT NULL,
    CONSTRAINT "users_email" UNIQUE ("email"),
    CONSTRAINT "users_id" PRIMARY KEY ("id")
) WITH (oids = false);


INSERT INTO "users" ("id", "email", "password", "role") VALUES
('118a9bca-b30e-360a-9acb-0f44498fa9cb',	'munoz.theophile@laposte.net',	'$2y$10$jdB9eypmLmifN5i5n5IwrOi9G6tySqYhsFAfJhHSgYmldTmuaKd3u',	0),
('bdbc09de-d523-34a5-bec7-743953a7cd2f',	'sgodard@vasseur.com',	'$2y$10$w5DrBc0vbyOyZnuTeNuBAuSCDX2NuSrVQAb9w4bN2FD0otn2tazBW',	0),
('33c59f91-10ff-3e5a-a4dd-ffb5e4d1d513',	'lledoux@dbmail.com',	'$2y$10$3jurBwm79Qc0DacDKeYele9UTwRlEd0Aadk.9lrYUw037cgJ91yZm',	0),
('98c2aeae-a2f1-382c-8c94-65a27d52f991',	'michel.georges@live.com',	'$2y$10$mwnKPXFa0d0FB34g1T8SJeV2VcsS7uJNDX3G7BnFvNKSsUV1l8e3i',	0),
('f930c1de-5fa2-3832-ba7c-b2d05a9dc2d4',	'rmahe@laposte.net',	'$2y$10$YI1njjpuWVSGtavG8zwn.ea06MIh0c1bPebFibyCoSFVjFjRgPxuu',	0),
('5957675b-b7b0-39ba-8b3a-920b2f7a523f',	'gimenez.alphonse@gregoire.com',	'$2y$10$0imxMNucxthxYFG6Xp4IOuO/VDE9Oc56CydZ..GLJRN63xvwmH2ze',	0),
('fd774f03-935f-39f5-ba95-2f01ffee28dd',	'leon.berger@bailly.org',	'$2y$10$zqoHN9o04tK1yve8fDQJMOsxwk8FxHxLtQ0l9XFcrz7ZuLg198wiW',	0),
('b5e1f6a4-cc8a-37b4-b23a-3772c3c30cbf',	'lopes.odette@sanchez.net',	'$2y$10$0taAHnOfVBWOWd4yLV8HT.oFVAzOylbCV1cQ15aOzhktPgsuvhD12',	0),
('9920d017-c728-3859-ab2e-31c4197c10f3',	'louis.arthur@mendes.net',	'$2y$10$vHMzzpN2rk896i5WAb/FBektzlCJGt9nGba8OWYFTZk4Y.wVao/f2',	0),
('ad9bfece-6520-3ff1-b497-f897e5bde84a',	'jean89@vidal.fr',	'$2y$10$rSC.zrWJTIZRELF1SzdZ/.bCKbpS7q5ZihHtFzGbWkiFFj9QF.Aq2',	0),
('438dff6a-4520-3a00-8b2e-16ea002cafef',	'william46@wanadoo.fr',	'$2y$10$r1RNVmpPrmVAryjx6dtXZu1tNRU9.TrsFygA.rg7B3Au1ZltzTW4G',	0),
('751aaf42-f6be-32f3-8a72-14250e710484',	'gturpin@costa.fr',	'$2y$10$Y0laIpLvYU14rXFMG2OAV..GO1kM6bsLcIXr0FiOIJp.coJ4PEQa.',	0),
('d7c22735-2d8a-30bb-bcc0-ab0a2acb56ac',	'henri29@live.com',	'$2y$10$uqcrqRJTV9zC7n81Ox1l0ePNEfCTrqedgmMhfqk0sB2WvopOXK0Qy',	0),
('aed94255-8dd3-378e-87b5-a1cd2d36eb08',	'jmarin@riviere.com',	'$2y$10$CaTTfmPJ5qSkmcERUz8Ul.K4h/W46TjS11ZgS6kSAAD1dCUKNEEnu',	0),
('ce09855e-6a9c-3a69-bb41-19f444ceedd8',	'wlebrun@yahoo.fr',	'$2y$10$fSvn4bnhdiJWg5gVVBcinedWSmYHQVSUMdYSy5Vb2emKJgVQqfxBO',	0),
('5e1837b3-3205-333d-80be-1b6617bacc5b',	'vmartin@hotmail.fr',	'$2y$10$UcjOqsbIf9eDcQYJKZ2UsuuAPDvm/tni3ANb6C6GE0Zp/Ou6iDGlW',	0),
('d48232be-5a3d-3aa6-9877-37f27c305f82',	'sophie.lambert@pineau.fr',	'$2y$10$ULrTSFMFNu072aOzSYsQFee3IpkBGDSbGJ5mnF3tqJFN/dNqyozqK',	0),
('a70dd64e-3b0e-3175-a5f5-d8012dcd8a73',	'nathalie93@sfr.fr',	'$2y$10$0u2nvIoPr3uuWcRMAjX7tedowPkg95TiFnpmbkpHGesG0AuLeInAO',	0),
('a6ace2ea-3bbf-35ca-bf65-1146caa118b6',	'cecile69@boulanger.com',	'$2y$10$SygCY6.efix594JFbNTAdO1/DxYfXJ1Ba0vMHikDeqS2yn22rfQWm',	0),
('74de52df-04ec-3364-bbf4-bf26e191cedb',	'alain15@charrier.fr',	'$2y$10$XGRcgAnLSdpU8Qk6bqRcruw25SoE.K66MksGXVCQw3r2lQv8CPEGy',	0),
('0c89eb6c-55dc-304a-a568-a682c9c6ceef',	'colette58@orange.fr',	'$2y$10$H1pdaffk2W0CdwmJEESsnOpVn1ZTvyAkR4SeJhDOZJEpIo04SpZO2',	0),
('11d611ed-fa3e-39e2-b5e0-018b00cef74a',	'gaillard.martin@deschamps.org',	'$2y$10$xrj.k0zFDLtmoUqLd/kQruRZ8qQVm9Jqo9zTkgSanLpBsTAJz9R4S',	0),
('d785b5be-c84e-3170-adbf-abb636460974',	'matthieu31@hotmail.fr',	'$2y$10$AcX8862cJm8ga7ETqN3C6.xC6HCDHK34xcNpImtpe0h1rZzmavMJO',	0),
('015bf648-9314-31cc-b536-34ad0d043c81',	'lebreton.alfred@dbmail.com',	'$2y$10$Rde/u3/PTR2w0fecg5IrkORrD8ji.IYmPlRFQ3/89uFChlUmagnsO',	0),
('a89bd373-a430-3394-8cbc-8ecd24f436e4',	'adelaide66@club-internet.fr',	'$2y$10$g2s/5AmqNRpCtCnebHbxne3MztlQ1nUBJfprjUWMuS6M8fgDKsBPW',	0),
('a45320ce-54ae-3778-abb6-ff1847a1d371',	'hugues14@free.fr',	'$2y$10$aQn78F6iroZEZCTjMLyM6.Z2uhcpdax2h/8Z4VKOXcawKTo3U.FFy',	0),
('6a7c3f13-723f-36be-ab11-dc40343e9158',	'ecordier@pereira.com',	'$2y$10$/yiM/t9ADClxC0pU42m7fOUDA6uVne/Mo7xBP/ClWMC3mbCk.niWm',	0),
('0a8b19ec-07d7-38db-abf0-e16aa1ff64c6',	'alfred30@maurice.com',	'$2y$10$xo1H/jzNK70ss4fj/vtiWugw4K5PrC1.L5bu9VilCOsDh5eTkWIPe',	0),
('6d28f713-2783-3e23-a493-0cc927a5a478',	'blemoine@sfr.fr',	'$2y$10$ZGt0irwCeaq0UjJvJ5.3zucYdDAnfKvLSlLlx19dEr9B1Ao4B6BE6',	0),
('500a2160-c09e-3698-bcb9-bbcadbddc8be',	'qremy@delmas.fr',	'$2y$10$IhAs2mRDiLWyKqVOmdKjp.6rKn//gMpRhjqegIqJqd1Y6eLExRHJi',	0),
('26d86b05-18a8-3ce4-baa2-e16b4cfd75f2',	'marcel93@joseph.com',	'$2y$10$Y1EUif1yER3Z9APJSsSVN.9aeBZMc889Ek8tuVxRSUC/40l3WGn3e',	0),
('e76799d6-e056-3bff-9e1a-9262c4382bdd',	'marthe.marques@wanadoo.fr',	'$2y$10$pFmfx/fo03nJiKINCO4WBelzpy/hAgdNauADovAefrp2838pNwjua',	0),
('b0d6d791-6e2a-339b-a94a-516f9b2ad825',	'alain37@deoliveira.fr',	'$2y$10$rXWwOWM5KiKf1gr5EhxlceNstfipPZNbK7PvWbmrLgKfSgpHhPacO',	0),
('18dfa463-6eb7-3d93-af61-bce88ec8e4d2',	'franck42@goncalves.com',	'$2y$10$BM0dAvuaSbJJIiI/1OEtEO18gDQrHLevVKGPAfcnNa6n3bAGYzIoG',	0),
('bfe259a1-1e4d-33d7-8d68-a86550e41245',	'collet.chantal@free.fr',	'$2y$10$N9m56Yo.ehvuocWiezupQ.FUJykbYRAzuSZYV60WneDwwb/br5u6W',	0),
('732f7a81-5dc7-35c2-908e-82cb14cd8196',	'rdelorme@orange.fr',	'$2y$10$25EgW0mt195whZWI63o7YuvLgU.vJIUUFNzq8QVJfdujABw1O9UeG',	0),
('c2f531cb-425e-37f8-9008-bcdc41598a3d',	'hortense.traore@yahoo.fr',	'$2y$10$76n.AEWjogyWInYbE4emCO/f6GWsFJ8F6MbJa/Z10M5Uh08Y7xyq2',	0),
('0f957154-6355-3a8c-b8d6-5c733f1673bc',	'lecoq.elisabeth@sfr.fr',	'$2y$10$DUkZETY70hc2wL71faRwO.l2RUXV.ZIg7xhp1cPshI.nlsM78NyEq',	0),
('73696f08-43d2-3601-8c9d-058c78a9fc7f',	'jacqueline57@sfr.fr',	'$2y$10$zqCWHMBIqOId8lw3nJJ9d.Tev4u7k3waD4JTNrYuaCXNq1Aj5v1oG',	0),
('a2923652-f03b-379b-b115-e355392ef0a5',	'qmace@guerin.fr',	'$2y$10$Wlom2tOGRcBnYILbcbNBhevzV4NroDklXbsvGlKIaaRbx0qyCBJay',	0),
('6b0bf17c-5c74-3e82-8c22-18ceba664a1f',	'chevallier.philippine@noos.fr',	'$2y$10$gYptWsSLmuNjD5iFobE..ujrLjyyRoD8fJW5Y9lkt48gwvNsp94pK',	0),
('51464726-48a7-30b3-875b-18af768ba8d7',	'andre.david@briand.org',	'$2y$10$mr5GbMgo2tgJ8/bYpNog7OalK4F2Qea14jsbo4Hw0Is.q/Fs4JIiG',	5),
('115d4ca8-7d83-3b30-959c-b5e0fd49285c',	'marguerite.faivre@gmail.com',	'$2y$10$7nnZE1ExOb1PI7/n93Q8V.5evgutW3TREHUiEH89leh7NBE9FVbue',	0),
('06aeb14e-fc32-3a32-bc2e-96f994c518fd',	'bouchet.stephane@noos.fr',	'$2y$10$6evUAj9ntgQtacGFuxCUv.EzaIXrJwHmm4OJEJ2EzL/UhLXpuql5W',	0),
('1b11af07-7782-3829-ac1a-a7c0d0216a5c',	'smallet@gmail.com',	'$2y$10$A6riWcaeoEtE2DLFipaXQe.FEWAgv0v.iUlntKo.Vlp1K63oB.5k.',	0),
('87eeb2c4-08aa-3aca-8ed3-5dac04daf26b',	'vlecoq@noos.fr',	'$2y$10$eRyaWf4MKvdFwPdcPcfz/OtHGrIQFfdOk6/EL3PzhxVIPbTXHscxK',	0),
('6a55fa16-1a54-3a78-86e6-4276d3f09cb0',	'bertin.claude@hotmail.fr',	'$2y$10$oLnskKl7wJllPLTM6HWj6ul2Wl7sC8qFzzbkxKYcQcY54eSa5Q8/m',	0),
('3e425365-fa3b-377a-be23-76bfad51c846',	'francois.margot@yahoo.fr',	'$2y$10$x17jAI6.gPH2qppUi0aZhOCnV9myhV9E4xXXzSZSSPuHHXJlPVNZK',	0),
('ac56c644-97f4-3d47-89a9-7f79a67ce6b3',	'gilles.dupuis@live.com',	'$2y$10$BHwKwE2wh4a0xSptEMmd6.UbQ/a9OmALm9GJfxbGClQZlB7NZajPK',	0),
('fe2b9ab3-666a-34bf-8f9e-91517d63fe7a',	'etienne.becker@orange.fr',	'$2y$10$GOu9tDdsPKE5Zv47eR6Ba.I5ZXJY2WBgBR0Tx5AKiSnBiRMJPhY6m',	0),
('98a3e7b5-8a62-3725-8f2c-f575d057bc48',	'arnaude73@tele2.fr',	'$2y$10$Ytxpmdq3bghb8Bmf9R0wKuJteCJNDQViK9wPldVUaVoIB/7pQP9Jy',	0),
('c401c65c-8d47-3fab-bab3-c3713a09ce06',	'tvaillant@besson.com',	'$2y$10$QDSXhfn2WMlp6IxONSWwwOFCDIawmnORZXM4KGrLZCirsR32VKen.',	10),
('40708f53-a81b-3f1f-aeed-886ce1e3be60',	'gonzalez.patricia@diaz.fr',	'$2y$10$0AY3fnR1BKX3QCjmjXWkgeV7iBTBNwo/JbRjJ7IM2lVzD6curwRZu',	10),
('d7b34ecf-f3c0-3f2d-84c9-be32f27f1a78',	'rey.alexandre@club-internet.fr',	'$2y$10$kOvoA0w0FfU2H7ZnXci9XeoEZuXrkKWsPmcz4MwNvhlk2cT0tI5jK',	10),
('28b72906-3cbf-3662-8806-b471d873343e',	'dumas.madeleine@leleu.com',	'$2y$10$Ri58fLnU3I9w.PkSXksTkegdf9aKFhp5zfAnE8EKAw/HT5bFh7oLS',	10),
('cf11bb88-f700-3b8e-8c17-745902612058',	'frederique.alexandre@gmail.com',	'$2y$10$6UjiB/OQ678aIaqVhSiBxexMilomwHT9veefT/fv0l2u9ZrJtFMOS',	10),
('011d2c1d-284b-3968-803a-81d25276d93e',	'valette.vincent@brun.com',	'$2y$10$igxaYeerx4lxmOqRifUb6u9riGiLM.2QXJ7nhYdXat20kef.c7UyG',	10),
('ada2fe33-aa08-3d48-b09d-d924c4a8f709',	'egeorges@moulin.fr',	'$2y$10$lXjklmtZ9b.iDSAKiV2iyuTMLr5rajfYgmG673NgfAFz1UheBmCfG',	10),
('3740ce08-d7ed-3f30-89dc-37c75705a5c0',	'pichon.audrey@noos.fr',	'$2y$10$oSRArZ2MLW4mEfHc8HWb6e/cly/hLpapIWXWOwUcl9FC1tnX0xxQm',	10),
('229f36ae-ff42-3b2b-a8bf-e0d90ea46448',	'ebreton@gmail.com',	'$2y$10$BYHfXW75oxcyUG5AuXlYtu6bjUPkGeZltSzn19FSbED77YBHw7BOC',	10),
('387a2731-2dd9-3c14-931f-2b024fa46b27',	'pascal.alexandre@becker.fr',	'$2y$10$8vU2eLuei7ncByKzHMr34uW9C9p5rbDjbtePJYGtBkkwnUUY5Mhcu',	10),
('06624069-b6dc-379a-af32-84b2c7f92703',	'qjoubert@fernandez.com',	'$2y$10$GSa6TjDzRC7XinIUsf73iOaMB.Tws0EB3RKohwJJfQDVpSTChatoW',	10),
('7edc6c16-dfaf-3dd0-b88a-4c3e303cf243',	'nathalie52@vidal.com',	'$2y$10$HeheQbEiMtmCJkM5mwG9O.ZKuJzKfLI2d5wfHVLmeN7k0.zFevyNO',	10),
('cf3dc7df-a4d7-3a8f-9dba-acd4f2d95f1d',	'susan.bonnin@deschamps.fr',	'$2y$10$ixnavMZvartqLWeWU1dCZ.WJi5JUoI7qrij3sIhLpFcVcR5J3SSTi',	10),
('8dcdc828-b90e-30d3-a30a-71890bccb9c3',	'wvallee@club-internet.fr',	'$2y$10$7IFghcui7dIOjSb8AUgcauObIXzzc25UcaTo61w8h0sa8vJDiir3G',	10),
('5991acf8-b757-3556-b1d9-5cfcf52204e0',	'zmichel@salmon.fr',	'$2y$10$oQzBL573aywKWUeWrVhtwu.EZsY2iikWqn7Yu30U5jXtrTjSJDlve',	10),
('dd468533-344d-3141-b6da-945fd273e3f8',	'patrick.sanchez@guilbert.net',	'$2y$10$mywvCQd1WEhjKBGyHwYlfeTbZcU1UX9EzbteeVoMdSOPYX0ZcDx8G',	10),
('656a298f-7f68-3729-b6d4-a015a71192ae',	'charles.bousquet@sfr.fr',	'$2y$10$i0NXAYTXiPtxLMdgSeUWJu1wiyeu.DtR65c4Rp8aN1pPSQb.TJGne',	10),
('718ef498-410d-3545-b326-423f8638ae01',	'isalmon@nguyen.com',	'$2y$10$oeTAC9PVNVRPN3ibaDQ9zOLnSo97X6hoAiLWwwZgqr/0mHLpM6aaK',	10),
('91254fa2-3c14-3bc1-83d4-4c8c8f17979a',	'pauline89@blanchet.fr',	'$2y$10$AJfPvhGYOR7gIjjbUnuxS.i0AAGCE5l3E/nX0xaIMOJNoX.N6dnfe',	10),
('93580ecf-1453-33e3-be82-dcd1f315100a',	'dasilva.eugene@hotmail.fr',	'$2y$10$y/je/XY90yZEzWCcq31IJ.44Oannd7KPoJ0hJfrpO4IIZDL7h6XnC',	10),
('ee2f80f8-18d4-32ce-9e0b-339a5d266efa',	'obourgeois@leduc.net',	'$2y$10$Uod5jmG192.2Tvf9ZsYJFuzeW3.qFAE38CKuar0nmZLVa9kgFbwNK',	10),
('3ad5f7ae-8699-34c7-824d-25919c7752be',	'guillaume90@boulay.com',	'$2y$10$kRUvFx0QCeRAiJ70wAHTPufuaQl5GcHsPqK1qHc06FqvhH3amNsS.',	10),
('add2e46a-8465-3aea-8f61-3129247d6b11',	'noel.descamps@tele2.fr',	'$2y$10$lBtNDO46UE75B4ueBe7TCu4T3HNlADlSRqXnSJ0/qGNSJ7PvgrKK2',	10),
('346db59f-d583-31ac-aba8-a36043b4865c',	'jreynaud@free.fr',	'$2y$10$OsduIH9Xr06u4jUbswSy7.Y0dVlYfBvVfhcYy8KIuCktgCyly32d2',	10),
('a7df1a42-1cb3-3fda-8b7e-e2151aac0987',	'lthibault@langlois.fr',	'$2y$10$yN1VszGhFzVr2FgLu74vK.kHqeZCiXrXk36LPWfkC69HAVoIZMIJi',	10),
('1af9b1f6-b4d9-333b-800d-9717a5a2cf5b',	'steixeira@potier.com',	'$2y$10$sfEeiBZDd2vCbqPHwmWIw.kjU7DgEhfeoy1ahY8xYXklq41fXTTv.',	10),
('155aeb3f-cdf3-38a0-b5a0-e2ace53540a9',	'aurore01@joseph.com',	'$2y$10$bWKN2Jbg9OPLeUcjmGeFiuvATLulL/pmBmMCk5ZUrUmwMPRTbelLS',	10),
('33ccfb21-15a2-3230-ab38-2cdab4ff1581',	'herve.valerie@gauthier.com',	'$2y$10$c1YKwuV/Husj/mw/jFC2fe/3/pp0K0A0N3xlbA5i97K0ul8YSKHv6',	10),
('2a7bb5df-4532-3c7b-82dc-f2f1b50e69e1',	'boutin.josephine@noos.fr',	'$2y$10$.bSa13HzicuaXhMTI3Ha9.5r7tDuJYzj7yle/17uPojiyBJUScBci',	10),
('ac6c2c16-ba55-3c45-8fdf-5043ab447eaf',	'robert.chretien@collet.com',	'$2y$10$343H/t.BBVlT4F3tFpfMmuFxiQiJUrof7k3oZTnHPSC8MMxNdtLei',	10),
('b773fa5f-fae5-3435-b2e8-ebc7b79f3281',	'zoe.prevost@francois.org',	'$2y$10$ZhDWHC6LSF3FT.HDrjaK0ONOmupsRpiXWZdnrrm9unKUv.Ut6Ee06',	10),
('c3f67135-26f6-3e46-a50d-0352242c1283',	'manon92@club-internet.fr',	'$2y$10$SeVUiyxZRj7B5V0trXVUROTCNujBcgVVPOCbDfJPqJ92UvdyRRnMS',	10),
('37293bd9-1151-33cc-87ee-14632e01fd0a',	'genevieve08@club-internet.fr',	'$2y$10$eiaPavF9qe.ccS1QrrJGXu09/5QTNBfqnuxLQpBOtKxLNs5yPDU5K',	10),
('a7aff564-bf07-344f-8e0f-4be67c12d895',	'dbousquet@tele2.fr',	'$2y$10$X07ptuIpRMfveGFD1zXG6.yvXCU3yqw6Z7rqmIhwkmCTfHFzoTHaO',	10),
('b5373bdc-bac8-3c66-b819-85ce85e4d320',	'godard.henri@perez.com',	'$2y$10$i98RJO5wqDm5oxM4iUl2nO9lBKz.I4cBaXhfukYsGn9M2d1sdDZse',	10),
('e5fcb510-ef63-35d3-aff9-7590459fcf3a',	'wgoncalves@mathieu.com',	'$2y$10$DMfTDMQcYx4Tm3e/8PFTe.RPem2wQFqSJaekmhLsWEzT4dfZIwqLi',	10),
('30e0b5ed-faa7-32ee-a513-e54c70b9015f',	'guillon.mathilde@guibert.fr',	'$2y$10$HAm3rhnBukZgwdei.Fyd7OnYgZzljNVnADNFiMJuT1OHk.3k9pixi',	10),
('2c32033b-1f4d-315e-b285-3e6ea3b30381',	'tbaron@traore.org',	'$2y$10$KupMrosScVTl2bn7Sa8XmerPK6iOwvhVkmG0cTCNQRnrgGW3slJUW',	10),
('5b1ea8fb-2f9f-304f-bf57-53121d030dd0',	'bigot.julien@rocher.fr',	'$2y$10$a6KkplR2ht3G58kpIuBNMOVkqAvjq6P6gUJVFIHFaS0xBV9Sq0xvW',	10),
('5ca8cedd-a22b-3241-ad42-cb5eefaa1424',	'zbourgeois@wagner.com',	'$2y$10$rCDebMSxlOAxEvmlgCcR2.EDIH6HYkFqnbPsgQnz47r0jmQ/lbP0i',	10),
('47e95999-4406-32f2-8b84-dc3fc31d7ef9',	'emilie84@vasseur.com',	'$2y$10$RsP4LpKcQd8ybNjdSnwihO1gu1maTM9E7epB2y/X/.IGE1IB.2Fi.',	10),
('88016ea3-9e18-319f-9dfd-62a12bfb47a5',	'richard48@etienne.com',	'$2y$10$mQESM3Clh9WK8KJmOo7G2eht2zkZwq/I/prz7IOwXnndGhJSWFavG',	10),
('7bea676a-c084-39d2-aaf8-707518a2f7d1',	'louis84@noos.fr',	'$2y$10$s/qT8gUttcGuJo7KGRVkKOHSwiTj8d/1gjT7oOontCILeDhm3iEnS',	10),
('836f4664-68c3-332a-8dae-78d289f53538',	'obourdon@briand.fr',	'$2y$10$Utg0YiFAc3szZxN/aZkuWu.qtwRSg.nXv.yuOAg0cEBez6cfprrVC',	10),
('d1c70b19-3c2e-3b8b-9c76-1f128576e567',	'zpeltier@hotmail.fr',	'$2y$10$1IVe7NmLu/kxBQiqMlmSE.0jQP1LT8iknn7qjjP6CDMH3Cs9Tjme6',	10),
('d7322940-05d8-3fce-a8df-6f8590b665b5',	'vincent53@tele2.fr',	'$2y$10$kAEyLebAmb3pJ0OmCvbLp.PgJsKngmNqmvCY9137HN0zrZQiXBJy.',	10),
('ed1a967f-774b-39a8-a408-ec38c725ccd2',	'marine01@michaud.net',	'$2y$10$frEMZG2xxbN8sRpKgTJwauxTCzOznB7.E6wz73b5RF2zm/uGVfs6S',	10),
('8fc950f8-ea0a-3a9c-a4e3-9f9fe9ab289e',	'uferrand@club-internet.fr',	'$2y$10$0EJ/M./8jrE2nDv5Ev2sW.yz93hP4Rei8M.iyUeZVKPAHn9F93pnK',	10),
('4749c6f8-8ddf-337c-b925-8655c5648fd6',	'christiane.daniel@dias.org',	'$2y$10$bSFceUsMkojNYsGnveWT1uqiFxMidDJ.LDTGtNbBs9fIcNnF4SHiK',	10),
('480da409-25e7-30ff-820f-690df48e2532',	'tdevaux@sfr.fr',	'$2y$10$/fRZM4Odcbzn.tT85Iosn.DYD36m.7DX5xOlJrk3vx4va5BT7s3Za',	10),
('e92e40a9-c8c6-3c6e-af27-467d8f9e2376',	'lbuisson@renaud.fr',	'$2y$10$7qfg7qXz2pyOOJ9jZrtCn.aimEchRYJzn2oTOUcJuUWSUJgeW3ld2',	5),
('d9f50226-17d9-3023-9355-ebf9665e041a',	'lombard.arthur@mahe.com',	'$2y$10$FwAve5QJ6KpD4IbQr5IIZe2fRogiqLxMJyFtCXIXko56x3pB.O99K',	5),
('83a47e08-a16b-3ca8-bf87-29b217b133ca',	'benoit.dupuy@poirier.com',	'$2y$10$O9Spt5WJw.QwMWsc7gFD0OCK.cBRs9UOt22QfpJeQiy80zR1vtTLq',	5),
('282c9401-cca2-317d-8805-4cadc098a15f',	'marianne.royer@marin.net',	'$2y$10$63gPf20LPoDiPKtec4x4cendFGiU51WzBQ3tBoFf99moMUW9srUFW',	5),
('26ff6373-cc46-30c8-a24f-7f88150831c5',	'becker.odette@orange.fr',	'$2y$10$NAefb0hpUQz20wrcGpERIucQHK0lS6HBOksCKtp8VIpbx0tJqn60W',	5),
('327bc24f-54db-35ba-af5b-f811f2a00095',	'slebreton@orange.fr',	'$2y$10$vpiHz3eXbfbERtTUY5ajqO6tYpijMwMYjYT.1zI8yq6No4IKM6Btm',	5),
('9778ecb7-9b84-3155-b0fc-d0a0b812bbb1',	'marcelle10@live.com',	'$2y$10$Kc4MOlZEfAF9MrH6LF7mh.TWmK4K9Ym.uReGF2SHb090ju6zbsX7W',	5),
('3b1bb2f9-3d53-3307-ba2d-2ce4c5e07f50',	'blaroche@laposte.net',	'$2y$10$65.TC916AHpziu77nr6ooOeDNiNggfSp/qTatiNek5rOoDboIkfu.',	5),
('1c61a317-8e03-3204-b02f-2117f466eaf0',	'leveque.alphonse@tele2.fr',	'$2y$10$sH4s2KO7.oZuVTEWPVKfWO/j8ZyOL7gTtGeVTjuPGMc9Sj4CP/5VO',	5),
('c94600fa-4177-3839-9518-4a1cdfee78ca',	'arthur.jacquot@dumas.net',	'$2y$10$dMmNu6vpUxK4z71hUsP3/.oyxe1EK/MYuHxH5b67Kn6xlUUPubL5C',	5),
('c9e763e0-745e-3165-adf1-0049756d107b',	'laurence45@gomes.org',	'$2y$10$vsFtlp9/Q8AYrLJj0r5raeI.XILJ.NfW3WuBxJE.XONIoDpGdetca',	5),
('f50388fa-1171-3150-8804-46d05553d96c',	'alfred21@hamon.fr',	'$2y$10$8y2wStQdW7dWCRuXWyQJBOl9O7OQx0mBj/dRXq6FfTfy7xq2zjDEm',	5),
('674aac5b-6759-3edf-ab34-bef53c675918',	'michel84@pons.fr',	'$2y$10$xw1YXcXc9pkUYXXgsa/t6.ko/I5bs8CXt1vsdNDgXmqF798twzbNO',	5),
('fcc3f623-d19d-3908-bddc-748542c35dce',	'vidal.caroline@klein.com',	'$2y$10$EW/Ry81S8xV6nmd/ONLHb.zyE7LYaJ2bsycyeS3BZZUIq2M8/V22G',	5),
('96ed2902-5426-34c8-9d38-74d44be38b82',	'fleblanc@gros.fr',	'$2y$10$teoY8bUvrRNR7odM28O5nO5f.SN.sL2ItEoFN3r8MLGxanQMjKPF6',	5),
('0a347510-8356-37a0-bada-977c00cccf24',	'antoinette.lombard@vallee.org',	'$2y$10$bRuSxqKjPPlbGsWJ22Oze.sFnhiYaqJAZrMMoLpXDdWmtLZmwnUCu',	5),
('a140c17d-90e8-3eb6-aa68-0d27c5ab75e8',	'caron.camille@peltier.fr',	'$2y$10$3QoKfhDC7MTJJ51981rWcukVi6Gt5agiFvuxWmrvQUSbeqYngRMaG',	5),
('cf91a239-dd88-332b-9c6c-b835a6cb5eb1',	'henri89@orange.fr',	'$2y$10$YMYkvBRmiYjzT94O0L8Y6.pMtWzgYYoaLgZuadagB.xF7If5rYIpO',	5),
('6af4fb70-c8e3-3ca6-b736-e29dd6b6b186',	'suzanne79@free.fr',	'$2y$10$karvgeLJtMn22MgX9aK/2uY7gRz7tGnxHd.0FozVAmzLuOx6MFHJy',	5),
('08ed67f8-d71f-3573-941c-20f8221c7f60',	'vincent21@hotmail.fr',	'$2y$10$96aSzK4JvlnLqqDAr68P3.SbFKbFSBzHKZw0j7JiIqwvAzPNEuk/S',	5),
('7b1291d3-b911-36d3-9dfd-f4d8d769bdad',	'bernadette.laine@leger.fr',	'$2y$10$62VYBu1X.mH62e0IS4lxzeNlJflcvlSVxs7vXNR7hncoisW76fcAS',	5),
('bb6f7e85-ad0c-3396-8eab-c2dc7da091ee',	'fbernier@noos.fr',	'$2y$10$q0DgY2ACHxwKoaljjktsLOlzMiRhO8aVlSBufy8tsuEVnDMRUJEt6',	5),
('c5bf7620-f290-30f6-b927-130489b6b6f9',	'alexandrie84@club-internet.fr',	'$2y$10$k3MtesaYK5gEAxAJP98YW.f0WMkiaI2rMliZYURBl69Kahn2b/Rhe',	5),
('9075d37a-add5-3d36-86ce-f30fa212a29a',	'victor59@mallet.fr',	'$2y$10$KBRRu8p5qVo8lkIxXJmZceEZbn/GzWx2GhEKO/Bwco37/q8DKaNxO',	5),
('3cc76c87-191c-331c-9277-94377b2a4488',	'pinto.martin@sfr.fr',	'$2y$10$hkk0h4pyGY25AeYa55ohCOmGIny78.oxef0KWw.1ua9ov3G4jLzEC',	5),
('5b189155-bf3c-36a1-8e03-25f77fff4250',	'margaux.diaz@delmas.org',	'$2y$10$Z.kxFOV6LCWTfBeyn2UE4uvHHu2Bl2sWDVW4F6237nrTlJUF4H5DO',	5),
('d5dceae0-36fc-3a78-ab12-cc71c3acbe8f',	'schauveau@philippe.fr',	'$2y$10$tmPpwUgA5IV6riJvnmOrC.CU0mj6JNl59VFMeo/WrV6MvqQMmB7Wa',	5),
('987fc01f-cf23-389d-a2ba-ca77486da752',	'peron.sebastien@tele2.fr',	'$2y$10$wgvvCx27hKeE7zT6gicvpuJNOkpMKmJ/XXg/WXGa/IctnVONfWQne',	5),
('b5a91ce6-15a5-3229-85e5-394b44c25549',	'dgarnier@tessier.com',	'$2y$10$q.8yc6dOInf3mPeqigTqsukeRnzAGt31JJIivABpgkbb9OK7hr8UG',	5),
('f374fb1b-74f2-39a5-86cd-b1d1154f0fea',	'josephine87@orange.fr',	'$2y$10$hSAuZpQ3qa3MlnLQh5D8Uubl8vDUS7zGwxjzQXp4VrMwAv1F1G1mW',	5),
('662dbdfb-5aa6-313f-86f6-693f3d885587',	'bertrand12@live.com',	'$2y$10$5E3iUQvXprFDBsiCoajJU.Kwy2hhwoonFhq8U9R7poCD0lgpraFBO',	5),
('4c13787f-7f37-3275-b08c-1e48f5401795',	'charrier.margot@free.fr',	'$2y$10$/WKCySXY7QgO6nSpYbgIceBmkMOo1m8Uo3d8/wRjbKz7gCs1FHoQO',	5),
('0732cbbd-732d-3068-a112-4b1e4308f161',	'susan.levy@guerin.com',	'$2y$10$HRMZJQr3iKJ8pSMXcBWqwuJbCwy5VSsr76AHCKtaK7UfTB10xStaG',	5),
('0f14673f-77dc-3f90-b06b-718319525074',	'vpeltier@meyer.net',	'$2y$10$R4I.b1iuR.e.BOGsXgWtK.jX4TQ2g7T/4iFzWu3RgPhrlDJcBr/wi',	5),
('a880d501-949d-3912-bbbd-3c9e08aac683',	'bertrand58@petit.fr',	'$2y$10$LApXS4XjXXN8TWSDqNJ8pewXew0j6h7iUO8H5suxpKT7hErdS/hfC',	5),
('26a1dd81-100c-3140-9cab-e2151e0ead08',	'emile.gonzalez@lebrun.net',	'$2y$10$1XMtpKcul060X5zYtNiR5eA5jdYaWcfOzhX41NGywNxZqXxR3hsqK',	5),
('b2a1f915-f2f6-351e-9a4f-43949842b4b8',	'aurelie.nguyen@gmail.com',	'$2y$10$YtX0jmJpY/R6YJtk9u2IMu3Do6KBFfNe5FaHsrtduxUD0X8DELaLS',	5),
('ba055ee7-4cee-317c-8d3d-79dd73db6078',	'robert.morel@fernandez.fr',	'$2y$10$UL12dE7B99GWxF6yTrS4V.AYk8iDJhjavT81H1o9JXIbjlkXpuoda',	5),
('ce7c82fa-a272-3181-887e-09b316c21d92',	'francois.texier@dbmail.com',	'$2y$10$eqiFl6O2WBX2l0aTNh6a8ugfGy/boGDfCbugEIlOjMEUYOxz73k1K',	5),
('048d0b23-56db-34b1-897a-44d7ebd921d6',	'clemence.jourdan@arnaud.com',	'$2y$10$uKhiRAIpLdQnSw/4KcAKwungHfGzxAO99VC9BfEDUwNAnmFV2Tziu',	5),
('3266f07d-a457-350c-99fa-5478fa12c1a4',	'elambert@samson.fr',	'$2y$10$AexAl9Sj5UspIQS2w5hYeeDy6lXKznfSAYBLkfYC/VS12uTb6A5za',	5),
('4f437fb1-c0a0-3b60-b2dc-2cd97b5f7732',	'gilles.hugues@live.com',	'$2y$10$bKncCRi5h1xh7aor/iv8dOARR7gHY.khwAn0WWLkALyChFbeI.mMO',	5),
('a4bf20cd-2c16-3c93-bf30-1c5aa6f9e370',	'eugene76@dossantos.org',	'$2y$10$wndxS.M49JBmAVWMrebYL./rH3qDzwDPHfXpsde9e5M226H5d/PSa',	5),
('ea568716-fbfa-3723-a893-a097061d9a1a',	'christiane.charpentier@langlois.fr',	'$2y$10$yhePSjeofzeSdirvWwvDJODn8XOW4FTy5BAW33Y//5Z9rJwmq1DNO',	5),
('cc27b401-aef3-38a9-8c52-0ec6117f9c67',	'pasquier.leon@girard.com',	'$2y$10$GHKykSHCSrmnjbutTg6ES.aUFhVkEK23q/QhBsfcaEGJvY0a7Rg2i',	5),
('679eaab9-c6a8-3a51-9ebb-8f5a073709d1',	'simone.jacquet@blot.com',	'$2y$10$uA5TwXkF.tKEZEVrgVOZx.0LWG.L4yTAVv0nYDVCz9GDk0pf0p2Ja',	5),
('0a6c75ca-4d2a-3786-92c9-1d5629cc23e2',	'aime92@leclerc.com',	'$2y$10$DkXd5n8ow07ZMQkbCdVS.e76KW8jJefhaFeThsASljLGh702rBbDy',	5),
('a115c355-fc49-3f16-93a9-275ea42ed489',	'bweiss@yahoo.fr',	'$2y$10$6PlgoWDGd2IUdsLxrAObheiTtQyhSIKaDEqapmb/9X1/a1uEiFTfm',	5),
('1016324e-7a32-3fca-9844-6ed51debaefb',	'guillaume.costa@dbmail.com',	'$2y$10$UwhIEYM7.W8YfnmA.R8yHuJIFg1OhRmQldh/XRLIB9oJ865dLP3ly',	5),
('fd774f03-935f-39f5-ba95-1e01feef28dd', 'test@test.com', 'testpass', 5);

\c praticien_db
--create table praticien
DROP TABLE IF EXISTS "praticien";
CREATE TABLE "public"."praticien" (
    "nom" character varying NOT NULL,
    "prenom" character varying NOT NULL,
    "adresse" text NOT NULL,
    "telephone" character varying NOT NULL,
    "specialite_id" character varying NOT NULL,
    "id" uuid DEFAULT gen_random_uuid() NOT NULL
) WITH (oids = false);


DROP TABLE IF EXISTS "specialite";
CREATE TABLE "public"."specialite" (
    "label" character varying NOT NULL,
    "description" text NOT NULL,
    "id" uuid DEFAULT gen_random_uuid() NOT NULL
) WITH (oids = false);

-- Insert sample data for praticien_db
INSERT INTO praticien (nom, prenom, adresse, telephone, specialite_id) VALUES
('Dupont', 'Jean', '123 Rue de Paris', '0123456789', '1'),
('Martin', 'Marie', '456 Avenue de Lyon', '0987654321', '2');

INSERT INTO specialite (label, description) VALUES
('Cardiologie', 'Spécialité médicale concernant le cœur et les vaisseaux sanguins'),
('Dermatologie', 'Spécialité médicale concernant la peau');

-- Create tables and insert data for rdv_db
\c rdv_db
DROP TABLE IF EXISTS "rdv";
CREATE TABLE rdv (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    patient_id UUID NOT NULL,
    praticien_id UUID NOT NULL,
    duree INT NOT NULL,
    specialite_id VARCHAR(255) NOT NULL,
    rdv_date TIMESTAMP NOT NULL,
    status VARCHAR(50) NOT NULL
);
-- Insert sample data for rdv_db
INSERT INTO rdv ( patient_id, praticien_id, duree, specialite_id, rdv_date, status) VALUES
('35404a92-24ad-4cce-bd5e-a51e257f9787', 'd92d2a08-cc15-4888-87c4-5c5bbe8f1bfd',60 ,'9f6d8761-0004-4d41-ad53-09d201fccc85', '2023-10-01 10:00:00', 'scheduled'),
('4d067207-99ee-445e-9399-37435a602fdd', '22d6c86e-261c-4f7a-9c36-8cbcbe87572d',30 ,'fdbe55f8-4bdd-4095-8cae-1946e9e9486b', '2023-10-02 11:00:00', 'completed');

-- Create tables and insert data for patient_db
\c patient_db
DROP TABLE IF EXISTS "patient";
CREATE TABLE "public"."patient" (
    "nom" character varying NOT NULL,
    "prenom" character varying NOT NULL,
    "adresse" text NOT NULL,
    "telephone" character varying NOT NULL,
    "id" uuid DEFAULT gen_random_uuid() NOT NULL
) WITH (oids = false);
-- Insert sample data for patient_db
INSERT INTO patient (nom, prenom, adresse, telephone) VALUES
('Doe', 'John', '123 Main St', '555-1234'),
('Smith', 'Jane', '456 Elm St', '555-5678');
