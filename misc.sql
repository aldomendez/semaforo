SELECT * 
FROM
(
SELECT facility, device, test_dt
FROM dare_pkg.mtemp_qube_prod
WHERE test_dt > SYSDATE-.001
AND facility = 'LE1-5617'
ORDER BY test_dt DESC
)
WHERE ROWNUM =1;

-- pasa saber cual maquina corre que cosa:
SELECT system_id,step_name FROM lr4_shim_assembly GROUP BY system_id, step_name ORDER BY system_id;

SELECT * FROM phase2.los_assembly --> Cybonders
SELECT * FROM phase2.pd_assembly --> Cybonders
SELECT * FROM phase2.lr4_shim_assembly --> Cybonders

SELECT * FROM dare_mrc.purge_norm
SELECT * FROM dare_mrc.liv_header_prod
SELECT * FROM dare_mrc.dyn_wave_prod

SELECT * FROM phase2.osa_functional_test --> Cytest OSA 10G
SELECT * FROM phase2.liv_test_35 --> Cytest OSA 10G
SELECT * FROM phase2.liv_test_40 --> Cytest OSA 10G
SELECT * FROM phase2.osa_functional_test_25g --> Cytest Fibest

SELECT * FROM dare_pkg.mtemp_qube_prod --> multitemp Engines
SELECT * FROM dare_pkg.wl_test_prod --> WL 161x
SELECT * FROM dare_pkg.ber_prod --> BER 161x
SELECT * FROM dare_pkg.eml10gb_prod --> ber 162x

select * from phase2.flex_attach

SELECT * FROM dare_pkg.fiber_weld_prod --> Welders engines

SELECT * FROM apogee.pkg_ther_sweep@mxappsro --> Screening IV Engines
SELECT * FROM pkg.screen_test@mxappsro --> Screening DC Engines