/**
 * Billboard Pro — front-end behaviour. Vanilla JS, no dependencies.
 */
(function () {
	'use strict';

	var d = document;
	var cfg = window.BBP || { i18n: {} };
	d.documentElement.classList.add('js');

	function $(sel, ctx) { return (ctx || d).querySelector(sel); }
	function $$(sel, ctx) { return Array.prototype.slice.call((ctx || d).querySelectorAll(sel)); }

	/* ---------- Header: scroll state + mobile nav ---------- */
	var header = $('#site-header');
	function onScroll() {
		if (header) { header.classList.toggle('is-scrolled', window.scrollY > 40); }
	}
	window.addEventListener('scroll', onScroll, { passive: true });
	onScroll();

	var toggle = $('.nav-toggle');
	if (toggle) {
		toggle.addEventListener('click', function () {
			var open = d.body.classList.toggle('nav-open');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
		d.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && d.body.classList.contains('nav-open')) {
				d.body.classList.remove('nav-open');
				toggle.setAttribute('aria-expanded', 'false');
				toggle.focus();
			}
		});
		$$('#primary-nav a').forEach(function (a) {
			a.addEventListener('click', function () {
				d.body.classList.remove('nav-open');
				toggle.setAttribute('aria-expanded', 'false');
			});
		});
	}

	/* ---------- Reveal on scroll ---------- */
	function observeReveal(ctx) {
		var items = $$('.reveal:not(.is-visible)', ctx);
		if (!('IntersectionObserver' in window)) {
			items.forEach(function (el) { el.classList.add('is-visible'); });
			return;
		}
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				if (en.isIntersecting) {
					en.target.classList.add('is-visible');
					io.unobserve(en.target);
				}
			});
		}, { rootMargin: '0px 0px -8% 0px' });
		items.forEach(function (el, i) {
			el.style.transitionDelay = (i % 4) * 70 + 'ms';
			io.observe(el);
		});
	}
	observeReveal();

	/* ---------- Animated counters ---------- */
	var counters = $$('[data-count]');
	if (counters.length && 'IntersectionObserver' in window && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		var cio = new IntersectionObserver(function (entries) {
			entries.forEach(function (en) {
				if (!en.isIntersecting) { return; }
				var el = en.target;
				cio.unobserve(el);
				var raw = el.getAttribute('data-count');
				var m = raw.match(/^([^0-9]*)([0-9.,]+)(.*)$/);
				if (!m) { return; }
				var target = parseFloat(m[2].replace(/,/g, ''));
				var decimals = (m[2].split('.')[1] || '').length;
				var start = null;
				function step(ts) {
					if (!start) { start = ts; }
					var p = Math.min((ts - start) / 1400, 1);
					var eased = 1 - Math.pow(1 - p, 3);
					el.textContent = m[1] + (target * eased).toFixed(decimals) + m[3];
					if (p < 1) { requestAnimationFrame(step); } else { el.textContent = raw; }
				}
				requestAnimationFrame(step);
			});
		}, { threshold: .6 });
		counters.forEach(function (c) { cio.observe(c); });
	}

	/* ---------- Inventory filters (AJAX + history) ---------- */
	var filterForm = $('[data-bbp-filter]');
	var results = $('[data-bbp-results]');
	var pager = $('[data-bbp-pagination]');
	var countEl = $('[data-count-target]');
	var sortSel = $('[data-bbp-sort]');
	var reqId = 0;

	function formParams(page) {
		var fd = new FormData(filterForm);
		if (sortSel && sortSel.value) { fd.set('sort', sortSel.value); }
		var qs = new URLSearchParams();
		fd.forEach(function (v, k) { if (v !== '') { qs.set(k, v); } });
		if (page && page > 1) { qs.set('paged', page); }
		return qs;
	}

	function runFilter(page, push) {
		if (!filterForm || !results || !cfg.ajaxUrl) { return; }
		var qs = formParams(page);
		var body = new URLSearchParams(qs.toString());
		body.set('action', 'bbp_filter');
		body.set('nonce', cfg.nonce);
		var my = ++reqId;
		results.classList.add('is-loading');
		results.setAttribute('aria-busy', 'true');

		fetch(cfg.ajaxUrl, { method: 'POST', credentials: 'same-origin', body: body })
			.then(function (r) { return r.json(); })
			.then(function (json) {
				if (my !== reqId) { return; }
				if (!json || !json.success) { throw new Error('bad'); }
				results.innerHTML = json.data.html;
				if (pager) { pager.innerHTML = json.data.pagination; }
				if (countEl) { countEl.textContent = Number(json.data.found).toLocaleString(); }
				observeReveal(results);
				if (push !== false) {
					var url = filterForm.getAttribute('action').split('?')[0];
					var shareable = new URLSearchParams(qs.toString());
					shareable.delete('paged');
					var s = shareable.toString();
					history.replaceState(null, '', url + (s ? '?' + s : ''));
				}
				if (page) {
					var top = results.getBoundingClientRect().top + window.scrollY - 120;
					window.scrollTo({ top: top, behavior: 'smooth' });
				}
			})
			.catch(function () {
				// Fallback: regular page load keeps filters fully functional.
				filterForm.submit();
			})
			.finally(function () {
				if (my === reqId) {
					results.classList.remove('is-loading');
					results.removeAttribute('aria-busy');
				}
			});
	}

	if (filterForm) {
		var t;
		filterForm.addEventListener('change', function () { runFilter(); });
		filterForm.addEventListener('input', function (e) {
			if (e.target.name !== 'kw') { return; }
			clearTimeout(t);
			t = setTimeout(function () { runFilter(); }, 400);
		});
		filterForm.addEventListener('submit', function (e) {
			e.preventDefault();
			runFilter();
			var panel = $('.filters-panel');
			if (panel && window.innerWidth < 1000) { panel.classList.remove('is-open'); }
		});
		if (pager) {
			pager.addEventListener('click', function (e) {
				var a = e.target.closest('a.page-numbers');
				if (!a) { return; }
				e.preventDefault();
				var p = a.getAttribute('data-page');
				if (!p) {
					var m = a.getAttribute('href').match(/\/page\/(\d+)|[?&]paged=(\d+)/);
					p = m ? (m[1] || m[2]) : 1;
				}
				runFilter(parseInt(p, 10), false);
			});
		}
	}
	if (sortSel) {
		sortSel.addEventListener('change', function () {
			if (filterForm) {
				runFilter();
			} else {
				var u = new URL(window.location.href);
				u.searchParams.set('sort', sortSel.value);
				window.location.href = u.toString();
			}
		});
	}
	var fToggle = $('.filters-toggle');
	if (fToggle) {
		fToggle.addEventListener('click', function () {
			var panel = $('#filters-panel');
			var open = panel.classList.toggle('is-open');
			fToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	}

	/* ---------- Forms: validation + AJAX submit ---------- */
	function setError(field, msg) {
		var wrap = field.closest('.field');
		var err = d.getElementById(field.getAttribute('aria-describedby'));
		if (wrap) { wrap.classList.toggle('has-error', !!msg); }
		if (err) { err.textContent = msg || ''; }
		field.setAttribute('aria-invalid', msg ? 'true' : 'false');
	}

	function fieldMessage(f, form) {
		var val = f.type === 'checkbox' ? (f.checked ? '1' : '') : f.value.trim();
		if (f.required && !val) {
			return cfg.i18n.required;
		}
		if (f.type === 'email' && val && !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(val)) {
			return cfg.i18n.email;
		}
		if (f.name === 'end_date' && val) {
			var s = form.querySelector('[name="start_date"]');
			if (s && s.value && val < s.value) { return cfg.i18n.dates; }
		}
		return '';
	}

	function fields(form) {
		return $$('input, select, textarea', form).filter(function (f) {
			return f.name && f.type !== 'hidden' && f.name !== 'bbp_hp';
		});
	}

	function validate(form) {
		var first = null;
		fields(form).forEach(function (f) {
			var msg = fieldMessage(f, form);
			setError(f, msg);
			if (msg && !first) { first = f; }
		});
		if (first) { first.focus(); }
		return !first;
	}

	$$('[data-bbp-form]').forEach(function (form) {
		// Re-check a field the user is correcting, without moving focus.
		fields(form).forEach(function (f) {
			var recheck = function () {
				if (f.getAttribute('aria-invalid') === 'true') { setError(f, fieldMessage(f, form)); }
			};
			f.addEventListener('blur', recheck);
			f.addEventListener('change', recheck);
		});
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var okBox = form.querySelector('.form-alert--success');
			var errBox = form.querySelector('.form-alert--error');
			okBox.hidden = true;
			errBox.hidden = true;
			if (!validate(form)) { return; }
			if (!window.fetch || !cfg.ajaxUrl) { form.submit(); return; }

			var btn = form.querySelector('[type="submit"] .btn__label');
			var label = btn ? btn.textContent : '';
			if (btn) { btn.textContent = cfg.i18n.sending; }
			form.classList.add('is-sending');

			fetch(cfg.ajaxUrl, { method: 'POST', credentials: 'same-origin', body: new FormData(form) })
				.then(function (r) { return r.json().then(function (j) { return { status: r.status, json: j }; }); })
				.then(function (res) {
					if (res.json && res.json.success) {
						form.reset();
						$$('.form-grid, [type="submit"], .form-note', form).forEach(function (el) { el.hidden = true; });
						okBox.hidden = false;
						okBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
						if (typeof window.gtag === 'function') { window.gtag('event', 'generate_lead', { form: form.querySelector('[name="bbp_form"]').value }); }
						return;
					}
					var errors = (res.json && res.json.data && res.json.data.errors) || {};
					Object.keys(errors).forEach(function (k) {
						var f = form.querySelector('[name="' + k + '"]');
						if (f) { setError(f, errors[k]); }
					});
					if (errors._form) { errBox.querySelector('span').textContent = errors._form; }
					errBox.hidden = false;
				})
				.catch(function () {
					errBox.querySelector('span').textContent = cfg.i18n.error;
					errBox.hidden = false;
				})
				.finally(function () {
					form.classList.remove('is-sending');
					if (btn) { btn.textContent = label; }
				});
		});
	});

	/* ---------- Testimonial slider ---------- */
	$$('[data-slider]').forEach(function (track) {
		var sec = track.closest('section');
		$$('[data-slide]', sec).forEach(function (b) {
			b.addEventListener('click', function () {
				var card = track.firstElementChild;
				var w = card ? card.getBoundingClientRect().width + 28 : 300;
				track.scrollBy({ left: b.getAttribute('data-slide') === 'next' ? w : -w, behavior: 'smooth' });
			});
		});
	});

	/* ---------- Sticky CTA on billboard pages ---------- */
	var sticky = $('.bb-sticky-cta');
	var hero = $('.bb-hero');
	var inquiry = $('#inquiry');
	if (sticky && hero && 'IntersectionObserver' in window) {
		var heroVisible = true;
		var formVisible = false;
		var update = function () {
			var show = !heroVisible && !formVisible;
			sticky.classList.toggle('is-visible', show);
			sticky.setAttribute('aria-hidden', show ? 'false' : 'true');
			var link = sticky.querySelector('a');
			if (link) { link.tabIndex = show ? 0 : -1; }
		};
		new IntersectionObserver(function (en) { heroVisible = en[0].isIntersecting; update(); }).observe(hero);
		if (inquiry) { new IntersectionObserver(function (en) { formVisible = en[0].isIntersecting; update(); }).observe(inquiry); }
	}

	/* ---------- Table of contents + scrollspy ---------- */
	var toc = $('[data-toc]');
	if (toc) {
		var heads = $$('.guide-body h2[id]');
		var list = toc.querySelector('ol');
		if (heads.length < 2) {
			toc.hidden = true;
		} else {
			heads.forEach(function (h) {
				var li = d.createElement('li');
				var a = d.createElement('a');
				a.href = '#' + h.id;
				a.textContent = h.textContent;
				li.appendChild(a);
				list.appendChild(li);
			});
			if ('IntersectionObserver' in window) {
				var links = $$('a', list);
				var tio = new IntersectionObserver(function (entries) {
					entries.forEach(function (en) {
						if (en.isIntersecting) {
							links.forEach(function (l) { l.classList.toggle('is-active', l.getAttribute('href') === '#' + en.target.id); });
						}
					});
				}, { rootMargin: '0px 0px -70% 0px' });
				heads.forEach(function (h) { tio.observe(h); });
			}
		}
	}

	/* ---------- Lightbox ---------- */
	$$('[data-lightbox]').forEach(function (gal) {
		var links = $$('a', gal);
		if (!links.length) { return; }
		var idx = 0;
		var box = null;
		var lastFocus = null;
		function icon(name) {
			return name === 'close'
				? '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>'
				: '<svg class="icon" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 5l7 7-7 7"/></svg>';
		}
		function show(i) {
			idx = (i + links.length) % links.length;
			box.querySelector('img').src = links[idx].href;
		}
		function close() {
			if (box) { box.remove(); box = null; d.body.style.overflow = ''; if (lastFocus) { lastFocus.focus(); } }
		}
		function open(i) {
			lastFocus = d.activeElement;
			box = d.createElement('div');
			box.className = 'lightbox';
			box.setAttribute('role', 'dialog');
			box.setAttribute('aria-modal', 'true');
			box.innerHTML = '<img alt=""><button class="lightbox__close" aria-label="Close">' + icon('close') + '</button>' +
				(links.length > 1 ? '<button class="lightbox__prev" aria-label="Previous">' + icon() + '</button><button class="lightbox__next" aria-label="Next">' + icon() + '</button>' : '');
			d.body.appendChild(box);
			d.body.style.overflow = 'hidden';
			show(i);
			box.addEventListener('click', function (e) {
				if (e.target === box || e.target.closest('.lightbox__close')) { close(); }
				else if (e.target.closest('.lightbox__prev')) { show(idx - 1); }
				else if (e.target.closest('.lightbox__next')) { show(idx + 1); }
			});
			box.querySelector('.lightbox__close').focus();
		}
		links.forEach(function (a, i) {
			a.addEventListener('click', function (e) { e.preventDefault(); open(i); });
		});
		d.addEventListener('keydown', function (e) {
			if (!box) { return; }
			if (e.key === 'Escape') { close(); }
			if (e.key === 'ArrowRight') { show(idx + 1); }
			if (e.key === 'ArrowLeft') { show(idx - 1); }
		});
	});
})();
