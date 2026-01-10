-- Minimal EN seed for UI labels + page content. You can extend/replace later.

INSERT OR IGNORE INTO ui_translations (namespace, translation_key, lang, text, updated_at) VALUES
 ('nav','home','en','Home',strftime('%Y-%m-%dT%H:%M:%fZ','now')),
 ('nav','solutions','en','Solutions',strftime('%Y-%m-%dT%H:%M:%fZ','now')),
 ('nav','integration_levels','en','Integration Levels',strftime('%Y-%m-%dT%H:%M:%fZ','now')),
 ('nav','how_it_works','en','How It Works',strftime('%Y-%m-%dT%H:%M:%fZ','now')),
 ('nav','use_cases','en','Use Cases',strftime('%Y-%m-%dT%H:%M:%fZ','now')),
 ('nav','about','en','About',strftime('%Y-%m-%dT%H:%M:%fZ','now')),
 ('nav','contact','en','Contact / Book a Call',strftime('%Y-%m-%dT%H:%M:%fZ','now')),
 ('ui','language','en','Language',strftime('%Y-%m-%dT%H:%M:%fZ','now')),
 ('ui','theme','en','Theme',strftime('%Y-%m-%dT%H:%M:%fZ','now')),
 ('ui','light','en','Light',strftime('%Y-%m-%dT%H:%M:%fZ','now')),
 ('ui','dark','en','Dark',strftime('%Y-%m-%dT%H:%M:%fZ','now')),
 ('ui','book_call','en','Book a discovery call',strftime('%Y-%m-%dT%H:%M:%fZ','now')),
 ('ui','send_message','en','Send message',strftime('%Y-%m-%dT%H:%M:%fZ','now'));

INSERT OR REPLACE INTO page_translations (page_id, lang, title, meta_description, body_html, updated_at) VALUES
 (1,'en','Agentic_AI — Operational AI systems','We build AI systems that run parts of your operations with measurable business outcomes.',
  '<section class="hero reveal"><div class="container"><div class="hero-grid"><div class="hero-copy"><h1>AI that runs operations. Not hype.</h1><p class="lead">We design and deploy AI agents that handle repeatable operational work—so your team stays focused on decisions, relationships, and growth.</p><div class="cta-row"><a class="btn btn-primary" href="/contact">Book a call</a><a class="btn btn-ghost" href="/solutions">See solutions</a></div><div class="trust-row"><div class="trust-card">Outcome-driven</div><div class="trust-card">Secure by design</div><div class="trust-card">Integrates with your stack</div></div></div><div class="hero-art"><div class="workflow"><div class="node">Input</div><div class="edge"></div><div class="node">Agent</div><div class="edge"></div><div class="node">Ops Output</div></div><div class="subtle">Abstract workflow animation. No gimmicks.</div></div></div></div></section><section class="section reveal"><div class="container"><div class="grid-3"><div class="card"><h3>Replace tasks, not people</h3><p>Agents handle structured operational work. Your team owns strategy and approvals.</p></div><div class="card"><h3>Measured impact</h3><p>We define baselines, ship safely, and track cycle-time, cost, and accuracy.</p></div><div class="card"><h3>Enterprise-ready</h3><p>Auditability, access control, and data boundaries from day one.</p></div></div></div></section>',
  strftime('%Y-%m-%dT%H:%M:%fZ','now')),
 (2,'en','Solutions — 3 AI Agents','Three production-grade agent types to automate core operational workflows.',
  '<section class="hero compact reveal"><div class="container"><h1>Solutions</h1><p class="lead">Three agents, deployed with clear scope and controls.</p></div></section><section class="section reveal"><div class="container"><div class="grid-3"><div class="card"><h3>Ops Coordinator</h3><p>Routes work, enforces SOPs, escalates exceptions, and keeps queues flowing.</p></div><div class="card"><h3>Revenue Assistant</h3><p>Qualifies inbound, drafts follow-ups, updates CRM, and surfaces next best actions.</p></div><div class="card"><h3>Support Automator</h3><p>Resolves known issues, summarizes tickets, and flags risks with traceable context.</p></div></div></div></section>',
  strftime('%Y-%m-%dT%H:%M:%fZ','now')),
 (3,'en','Integration Levels','From quick wins to enterprise-grade automation.',
  '<section class="hero compact reveal"><div class="container"><h1>Integration Levels</h1><p class="lead">Choose the right depth for your risk profile and timeline.</p></div></section><section class="section reveal"><div class="container"><div class="stack"><div class="card"><h3>Level 1 — Assist</h3><p>Drafts, summaries, recommendations. Humans approve.</p></div><div class="card"><h3>Level 2 — Execute</h3><p>Performs actions in tools with guardrails and logging.</p></div><div class="card"><h3>Level 3 — Orchestrate</h3><p>Multi-step workflows across systems with exception handling.</p></div><div class="card"><h3>Level 4 — Enterprise</h3><p>SSO, audit trails, policy controls, monitoring, and scale.</p></div></div></div></section>',
  strftime('%Y-%m-%dT%H:%M:%fZ','now')),
 (4,'en','How It Works','A structured delivery method designed for reliability.',
  '<section class="hero compact reveal"><div class="container"><h1>How it works</h1><p class="lead">A simple process: scope, ship safely, then iterate.</p></div></section><section class="section reveal"><div class="container"><div class="grid-2"><div class="card"><h3>1) Operational mapping</h3><p>We identify repeatable work, constraints, and success metrics.</p></div><div class="card"><h3>2) Controlled automation</h3><p>We deploy agents with approvals, logs, and clear boundaries.</p></div><div class="card"><h3>3) Measurement</h3><p>We track outcomes and continuously improve reliability.</p></div><div class="card"><h3>4) Expansion</h3><p>We scale to adjacent workflows once value is proven.</p></div></div></div></section>',
  strftime('%Y-%m-%dT%H:%M:%fZ','now')),
 (5,'en','Use Cases / Industries','Practical use cases across operations-heavy teams.',
  '<section class="hero compact reveal"><div class="container"><h1>Use cases</h1><p class="lead">Where operational AI has immediate ROI.</p></div></section><section class="section reveal"><div class="container"><div class="grid-3"><div class="card"><h3>Professional services</h3><p>Briefing prep, follow-ups, document workflows.</p></div><div class="card"><h3>Logistics</h3><p>Exception handling, status updates, vendor coordination.</p></div><div class="card"><h3>SaaS</h3><p>Support ops, revenue ops, onboarding operations.</p></div></div></div></section>',
  strftime('%Y-%m-%dT%H:%M:%fZ','now')),
 (6,'en','About Agentic_AI','Consultant-grade delivery focused on outcomes.',
  '<section class="hero compact reveal"><div class="container"><h1>About</h1><p class="lead">We build systems that stand up in production—not demos.</p></div></section><section class="section reveal"><div class="container"><div class="grid-2"><div class="card"><h3>Positioning</h3><p>AI replaces operational tasks, not people. The goal is flow, consistency, and measurable impact.</p></div><div class="card"><h3>Delivery</h3><p>We ship with controls: approvals, logging, and clear failure modes.</p></div></div></div></section>',
  strftime('%Y-%m-%dT%H:%M:%fZ','now')),
 (7,'en','Contact / Book a Call','Talk to us about a workflow you want off your plate.',
  '<section class="hero compact reveal"><div class="container"><h1>Contact</h1><p class="lead">Tell us what you want automated. We will propose a safe, measurable rollout.</p></div></section>',
  strftime('%Y-%m-%dT%H:%M:%fZ','now'));
