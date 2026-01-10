-- Seed minimal page translations for other supported languages.
-- Content is intentionally concise and can be replaced with full native copy later.

INSERT OR REPLACE INTO page_translations (page_id, lang, title, meta_description, body_html, updated_at) VALUES

-- HOME
(1,'fr','Agentic_AI — Systèmes opérationnels IA','Nous déployons des systèmes IA qui prennent en charge des tâches opérationnelles avec des résultats mesurables.','<section class="hero reveal"><div class="container"><div class="hero-grid"><div class="hero-copy"><h1>Des systèmes IA qui font tourner l''opérationnel.</h1><p class="lead">Nous automatisons des tâches répétables avec des garde-fous, de la traçabilité et des métriques claires.</p><div class="cta-row"><a class="btn btn-primary" href="/contact">Réserver un appel</a><a class="btn btn-ghost" href="/solutions">Voir les solutions</a></div></div><div class="hero-art"><div class="workflow"><div class="node">Entrée</div><div class="edge"></div><div class="node">Agent</div><div class="edge"></div><div class="node">Sortie</div></div><div class="subtle">Workflow abstrait, sobre et professionnel.</div></div></div></div></section>',strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(1,'es','Agentic_AI — Sistemas de IA operativa','Construimos sistemas de IA que ejecutan partes de tus operaciones con impacto medible.','<section class="hero reveal"><div class="container"><div class="hero-grid"><div class="hero-copy"><h1>IA que ejecuta operaciones. Sin humo.</h1><p class="lead">Agentes con límites claros, aprobaciones y trazabilidad. Enfocados en resultados de negocio.</p><div class="cta-row"><a class="btn btn-primary" href="/contact">Reservar llamada</a><a class="btn btn-ghost" href="/solutions">Ver soluciones</a></div></div><div class="hero-art"><div class="workflow"><div class="node">Entrada</div><div class="edge"></div><div class="node">Agente</div><div class="edge"></div><div class="node">Salida</div></div><div class="subtle">Flujo abstracto, profesional.</div></div></div></div></section>',strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(1,'pt','Agentic_AI — Sistemas de IA operacional','Construímos sistemas de IA que assumem tarefas operacionais com impacto mensurável.','<section class="hero reveal"><div class="container"><div class="hero-grid"><div class="hero-copy"><h1>IA que opera. Sem hype.</h1><p class="lead">Agentes com limites claros, aprovações e rastreabilidade. Foco em resultados.</p><div class="cta-row"><a class="btn btn-primary" href="/contact">Agendar chamada</a><a class="btn btn-ghost" href="/solutions">Ver soluções</a></div></div><div class="hero-art"><div class="workflow"><div class="node">Entrada</div><div class="edge"></div><div class="node">Agente</div><div class="edge"></div><div class="node">Saída</div></div><div class="subtle">Fluxo abstrato, premium.</div></div></div></div></section>',strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(1,'ar','Agentic_AI — أنظمة ذكاء تشغيلي','نبني أنظمة ذكاء اصطناعي تُشغّل أجزاء من عملياتك بنتائج قابلة للقياس.','<section class="hero reveal"><div class="container"><div class="hero-grid"><div class="hero-copy"><h1>ذكاء اصطناعي يُشغّل العمليات. بدون ضجيج.</h1><p class="lead">وكلاء بحدود واضحة، وموافقات، وتتبّع كامل. التركيز على النتائج.</p><div class="cta-row"><a class="btn btn-primary" href="/contact">حجز مكالمة</a><a class="btn btn-ghost" href="/solutions">عرض الحلول</a></div></div><div class="hero-art"><div class="workflow"><div class="node">مدخلات</div><div class="edge"></div><div class="node">وكيل</div><div class="edge"></div><div class="node">مخرجات</div></div><div class="subtle">سير عمل تجريدي وبأسلوب احترافي.</div></div></div></div></section>',strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(1,'zh','Agentic_AI — 运营型 AI 系统','我们构建可落地的运营型 AI 系统，带来可衡量的业务结果。','<section class="hero reveal"><div class="container"><div class="hero-grid"><div class="hero-copy"><h1>让 AI 跑运营，而不是讲故事。</h1><p class="lead">边界清晰、可审计、可度量的智能代理，让团队专注关键决策。</p><div class="cta-row"><a class="btn btn-primary" href="/contact">预约沟通</a><a class="btn btn-ghost" href="/solutions">查看方案</a></div></div><div class="hero-art"><div class="workflow"><div class="node">输入</div><div class="edge"></div><div class="node">代理</div><div class="edge"></div><div class="node">输出</div></div><div class="subtle">抽象流程动画，专业克制。</div></div></div></div></section>',strftime('%Y-%m-%dT%H:%M:%fZ','now')),

-- OTHER PAGES (use English body as placeholder, translated titles/meta)
(2,'fr','Solutions — 3 Agents IA','Trois types d''agents prêts pour la production.',(SELECT body_html FROM page_translations WHERE page_id=2 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(2,'es','Soluciones — 3 Agentes IA','Tres agentes listos para producción.',(SELECT body_html FROM page_translations WHERE page_id=2 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(2,'pt','Soluções — 3 Agentes IA','Três agentes prontos para produção.',(SELECT body_html FROM page_translations WHERE page_id=2 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(2,'ar','الحلول — 3 وكلاء','ثلاثة وكلاء جاهزون للإنتاج.',(SELECT body_html FROM page_translations WHERE page_id=2 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(2,'zh','解决方案 — 3 个智能代理','三类可上线的智能代理。',(SELECT body_html FROM page_translations WHERE page_id=2 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),

(3,'fr','Niveaux d''intégration','Du simple au niveau entreprise.',(SELECT body_html FROM page_translations WHERE page_id=3 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(3,'es','Niveles de integración','De simple a enterprise.',(SELECT body_html FROM page_translations WHERE page_id=3 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(3,'pt','Níveis de integração','Do simples ao enterprise.',(SELECT body_html FROM page_translations WHERE page_id=3 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(3,'ar','مستويات التكامل','من البسيط إلى المؤسسات.',(SELECT body_html FROM page_translations WHERE page_id=3 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(3,'zh','集成级别','从简单到企业级。',(SELECT body_html FROM page_translations WHERE page_id=3 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),

(4,'fr','Méthode','Process simple et fiable.',(SELECT body_html FROM page_translations WHERE page_id=4 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(4,'es','Cómo funciona','Proceso simple y fiable.',(SELECT body_html FROM page_translations WHERE page_id=4 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(4,'pt','Como funciona','Processo simples e confiável.',(SELECT body_html FROM page_translations WHERE page_id=4 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(4,'ar','كيف نعمل','عملية بسيطة وموثوقة.',(SELECT body_html FROM page_translations WHERE page_id=4 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(4,'zh','工作方式','结构化交付流程，强调可靠性。',(SELECT body_html FROM page_translations WHERE page_id=4 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),

(5,'fr','Cas d''usage','Cas concrets et pragmatiques.',(SELECT body_html FROM page_translations WHERE page_id=5 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(5,'es','Casos de uso','Casos prácticos.',(SELECT body_html FROM page_translations WHERE page_id=5 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(5,'pt','Casos de uso','Casos práticos.',(SELECT body_html FROM page_translations WHERE page_id=5 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(5,'ar','حالات الاستخدام','حالات عملية.',(SELECT body_html FROM page_translations WHERE page_id=5 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(5,'zh','应用场景','以运营为核心的落地场景。',(SELECT body_html FROM page_translations WHERE page_id=5 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),

(6,'fr','À propos','Crédibilité consultant, focus résultats.',(SELECT body_html FROM page_translations WHERE page_id=6 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(6,'es','Acerca de','Enfoque consultivo y resultados.',(SELECT body_html FROM page_translations WHERE page_id=6 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(6,'pt','Sobre','Entrega consultiva e foco em resultados.',(SELECT body_html FROM page_translations WHERE page_id=6 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(6,'ar','من نحن','نهج استشاري وتركيز على النتائج.',(SELECT body_html FROM page_translations WHERE page_id=6 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(6,'zh','关于我们','咨询级交付，聚焦业务结果。',(SELECT body_html FROM page_translations WHERE page_id=6 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),

(7,'fr','Contact / Appel','Parlez-nous de votre flux à automatiser.',(SELECT body_html FROM page_translations WHERE page_id=7 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(7,'es','Contacto / Llamada','Cuéntanos el flujo que quieres automatizar.',(SELECT body_html FROM page_translations WHERE page_id=7 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(7,'pt','Contato / Chamada','Conte-nos o fluxo que deseja automatizar.',(SELECT body_html FROM page_translations WHERE page_id=7 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(7,'ar','تواصل / حجز مكالمة','أخبرنا بما تريد أتمتته.',(SELECT body_html FROM page_translations WHERE page_id=7 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now')),
(7,'zh','联系 / 预约通话','告诉我们你想自动化的流程。',(SELECT body_html FROM page_translations WHERE page_id=7 AND lang='en'),strftime('%Y-%m-%dT%H:%M:%fZ','now'));
