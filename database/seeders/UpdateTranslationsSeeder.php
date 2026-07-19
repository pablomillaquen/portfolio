<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Project;
use Illuminate\Database\Seeder;

class UpdateTranslationsSeeder extends Seeder
{
    public function run(): void
    {
        $this->updatePosts();
        $this->updateProjects();
    }

    private function updatePosts(): void
    {
        $posts = [
            'ley-21719-proyecto-ingenieria' => [
                'excerpt_en' => 'The entry into force of Ley 21.719 represents much more than a regulatory change. It forces us to review how information systems collect, store, and use personal data. In this first article of the "Building Compliance" series, I explore why adapting to the new regulation should be approached as a software engineering problem and how evidence-based research becomes the first step before modifying any system.',
                'content_en' => <<<'MD'
# Ley 21.719 Is Not a Legal Project. It Is a Software Engineering Project.

On December 1, 2026, Law No. 21.719 will enter into force, regulating the protection and processing of personal data in Chile and creating the Personal Data Protection Agency. The new regulation redefines how organizations must process personal data and establishes principles, obligations, and rights that directly affect information systems. It is not merely a regulatory change: it is a change in the way we must design, develop, and operate software.

Over the past few months, I have spoken with professionals from various fields about this topic. One response is frequently repeated:

*"When the time comes, we will have to update privacy policies and add some changes to the system."*

That idea is understandable, but it is profoundly wrong.

Ley 21.719 does not represent simply a challenge for lawyers or compliance officers. It represents a software engineering challenge.

## Software Is What Processes Data

Every time an application registers a user, stores an address, sends an email, generates a PDF report, synchronizes information with another platform, or retains historical records, it is processing personal data.

That means virtually any modern system falls under the new regulation.

It does not matter if it is:

* an ERP;
* a CRM;
* a Human Resources platform;
* a hospital information system;
* a mobile application;
* SaaS software;
* a municipal portal;
* a university system.

They all process personal data.

And if the software processes data, the law also applies to it.

## The Real Challenge Is Not the Law

Reading a law is usually not the biggest problem.

The real challenge lies in answering questions that typically no organization can answer with certainty.

For example:

* What personal data does our system actually store?
* Where is that data located?
* Which modules use it?
* Who can access it?
* Which external services receive it?
* How long is it retained?
* Is it possible to completely delete it?
* Can we demonstrate all of the above with evidence?

Surprisingly, many organizations do not have objective answers to these questions.

And without those answers, it is impossible to assess the actual level of compliance.

## Modifying an Existing System Is Much Harder Than Building a New One

There is a widely held belief in software development:

*"If I were starting from scratch, I would do it much better."*

To some extent, it is true.

When a system is born with privacy by design, it is possible to define data models, processes, audits, and controls from day one.

But reality is different.

Most organizations do not start from scratch.

They work on systems that have been evolving for years.

Applications that have incorporated new features, integrations, reports, exports, automated processes, and hundreds of technical decisions made over a long period.

In those systems, personal data rarely lives in one place.

It replicates.

It travels.

It transforms.

It appears in databases, documents, exported files, backups, audit logs, emails, external services, and automated processes.

Modifying that ecosystem requires much more than adding a new screen or a consent checkbox.

It requires deeply understanding how the system works.

## Engineering Begins Before Writing Code

There is a natural tendency in our profession:

When faced with a new requirement, we start programming.

But when the requirement comes from a regulation like Ley 21.719, that approach is insufficient.

Before modifying the software, it is necessary to understand the problem.

That means researching.

Identifying obligations.

Relating them to the existing architecture.

Seeking evidence.

Determining gaps.

Establishing priorities.

And only then starting to design solutions.

In other words, implementation should be the result of research, not the starting point.

## Our Case Study

I am currently working on adapting SIGES, a management system for medical equipment used in the healthcare sector.

The healthcare sector presents a particularly demanding challenge because it routinely works with sensitive personal data, whose protection requires especially rigorous technical and organizational measures, in accordance with Ley 21.719.

However, the goal was never to develop isolated features to comply with a checklist of requirements.

The first decision was much simpler:

Not write a single line of code.

First we had to understand the system.

Understand how data flowed.

Determine which legal obligations actually impacted the existing architecture.

And collect objective evidence before deciding on any changes.

## Evidence-Based Engineering

That process ended up becoming a comprehensive research effort.

Instead of assuming answers, we decided to obtain evidence.

Instead of starting by implementing features, we built a baseline.

Instead of working on assumptions, we analyzed the actual behavior of the system.

That approach is part of EDSE (Evidence-Driven Software Evolution), a methodology oriented to studying existing systems before modifying them, using research questions, evidence collection, traceable decisions, and verifiable specifications.

Thanks to that process, we discovered that adapting a system to a new regulation is not just about developing new features.

It is, first, about understanding the software.

## This Series

This article launches a series where I will share the complete process we are developing to transform the requirements of Ley 21.719 into engineering decisions.

It will not be a legal series.

Nor will it be a superficial compliance guide.

It will be a series about applied research, software architecture, and evolution of existing systems.

Because, at the end of the day, complying with a regulation does not begin with writing code.

It begins with understanding the problem.

And in our case, we decided to face it exactly that way: as an evidence-based engineering problem.
MD,
            ],
            'edse-estudiar-ley-21719' => [
                'excerpt_en' => 'How do you transform a regulation into engineering decisions? In this second chapter of the "Building Compliance" series, I share the approach we used before modifying the software: formulating research questions, collecting objective evidence, documenting findings, building a baseline, and making traceable decisions. A process that began with adapting SIGES to Ley 21.719, but that can be applied to any existing system.',
                'content_en' => <<<'MD'
# How We Used EDSE to Study Ley 21.719 Before Modifying the Software

*Series: Building Compliance – Chapter 2*

In the previous article, I presented an idea that may seem counterintuitive to some:

> **Ley 21.719 is not only a legal challenge. It is a software engineering challenge.**

If we accept that premise, a second question immediately arises.

**Where do you start?**

The most common answer would be:

*"Let's start implementing the new features required by the law."*

We decided to do exactly the opposite.

Before writing a single line of code, we decided to research.

## The Problem with Starting by Programming

In software development, we are accustomed to receiving a requirement and quickly turning it into technical tasks.

Create a screen.

Modify a database.

Add an API.

Update a form.

But a regulation does not work like a traditional functional requirement.

The law does not say which tables to create.

It does not indicate how an architecture should be designed.

It does not explain where to implement a control.

What it delivers are obligations.

And transforming legal obligations into technical decisions requires first understanding the system that already exists.

## First Understand. Then Intervene.

The question stopped being:

*"What should we develop?"*

And became much more interesting:

*"What does our system actually need to comply with the regulation?"*

Answering that question required more than reviewing code.

We needed evidence.

## We Started by Formulating Research Questions

Instead of creating a task list, we began by defining questions that guided the entire research.

For example:

* What personal data does the system currently process?
* Where does it originate?
* In which modules is it stored?
* How does it flow between different components?
* Which obligations of Ley 21.719 affect each process?
* What controls currently exist?
* What evidence demonstrates their operation?
* What gaps appear between the current state and the required state?

These questions ended up becoming the axis of all subsequent work.

Each decision had to answer a specific question.

Not an opinion.

Not an intuition.

A verifiable question.

## Then Came the Findings

Every research effort produces discoveries.

In software engineering, those discoveries are often much more valuable than the first solutions imagined.

During the analysis, we found expected situations and others that were completely unexpected.

We discovered processes that already partially complied with some principles of the law.

We also found components where personal data appeared replicated in different locations.

Integrations appeared that needed to be reviewed.

Automated processes that required greater traceability.

And features whose adaptation would be considerably more complex than initially seemed.

Each finding modified our understanding of the system.

And, consequently, modified subsequent decisions.

## Every Claim Had to Be Backed by Evidence

One of the principles that guided the work was very simple:

**Assume nothing.**

Every conclusion had to be backed by objective evidence.

That meant reviewing:

* system architecture;
* data model;
* source code;
* user interfaces;
* workflows;
* configurations;
* integrations;
* existing documentation;
* observable application behavior.

Evidence ended up being much more important than opinions.

Because evidence can be reviewed.

It can be verified.

And it can be reused when the system evolves again.

## Building a Baseline

Once the evidence was collected, a new need emerged.

We needed to freeze the current state of the system.

Not to prevent its evolution.

But to know exactly where to start.

That initial snapshot constitutes what in engineering we call a baseline.

The baseline answers a fundamental question:

**What was the state of the system before the modifications began?**

Without that reference, it is very difficult to measure progress, justify decisions, or demonstrate that an improvement actually occurred.

## Decisions Stopped Depending on Opinions

Perhaps the most important change was this.

Meetings stopped revolving around phrases like:

*"I think we should..."*

or

*"It seems to me that..."*

And began to be supported by questions like:

* What evidence supports this decision?
* What finding justifies this change?
* What legal obligation are we addressing?
* What impact will it have on the existing system?

Decisions began to be the result of research.

Not personal preferences.

## A Repeatable Process

Although this research originated during the adaptation of SIGES to Ley 21.719, we quickly understood that the process was much broader.

The same stages can be applied to any organization that needs to adapt an existing system to a new regulation or face high-impact changes:

* formulate questions before intervening;
* collect objective evidence;
* document findings;
* establish a baseline;
* make traceable decisions.

It does not matter if the system belongs to the healthcare, education, logistics, industry, or services sector.

The principle remains the same.

First understand.

Then modify.

## What Comes Next

In the next article, I will share one of the most important discoveries of this research.

When we started reviewing a real application, we found something that probably also occurs in many other systems: personal data does not live in one place.

It travels.

It duplicates.

It transforms.

And understanding that journey ended up being one of the greatest challenges of the entire project.
MD,
            ],
            'trazabilidad-datos-ley-21719' => [
                'excerpt_en' => 'What happens when an organization analyzes its system from the perspective of data protection? In this third chapter of the "Building Compliance" series, I share one of the main findings of our research: a single piece of personal data can travel through multiple modules, documents, and processes within an application. Understanding that journey turned out to be much more important than implementing new features.',
                'content_en' => <<<'MD'
# What We Discovered When Analyzing a Real Application Before Adapting It to Ley 21.719

*Series: Building Compliance – Chapter 3*

In previous articles, I explained why we believe that adapting to Ley 21.719 should begin with research and not with implementation.

But that decision led us to an inevitable question.

**What actually happens inside a system when we start looking for personal data?**

The answer was much more complex than we imagined.

And it is probably very similar to the reality of many organizations.

## We Thought We Knew the System

As happens in most projects, there was documentation, accumulated knowledge, and a reasonable understanding of the application.

We knew what each module did.

We were familiar with the main features.

We understood the general architecture.

However, Ley 21.719 forced us to observe the system from a completely different perspective.

It was no longer enough to understand business processes.

Now we had to follow the journey of personal data.

And that completely changed the research.

## Data Never Stays in One Place

One of the first discoveries was that a single piece of personal data rarely lives in a single table or screen.

Once it enters the system, it begins a journey that crosses multiple processes.

For example, a name or email address may appear simultaneously in:

* user accounts;
* requests registered by the organization;
* work orders;
* technical reports;
* automatically generated PDF documents;
* emails sent by the system;
* historical records;
* audit logs;
* exported files;
* operational backups.

In other words, the data ceases to belong to a single module.

It becomes part of the complete behavior of the system.

## The Challenge Stopped Being Technical

At that point, we understood that the real challenge was not modifying a screen.

The challenge was answering questions like:

* How many copies of this data actually exist?
* Which processes generate them?
* Which components use them?
* Which ones are necessary?
* Which ones could be removed?
* Which ones must be retained for operational or legal reasons?

Answering those questions requires understanding the software's complete operation.

## Processes Also Generate Data

Another important observation was that personal data is not only stored.

It is also generated during daily operations.

Each interaction can produce new records.

Each change leaves evidence.

Each action feeds new logs.

Each document incorporates new information.

That means the volume of personal data constantly grows while the system is in operation.

It is not enough to review the main tables.

It is necessary to understand all processes that produce new information.

## Documents Are Also Part of the System

There is another very common assumption.

Thinking that the system ends at the database.

But during the research, we discovered that a large part of the information permanently leaves the application.

It appears in:

* PDF reports;
* downloadable documents;
* files sent by email;
* printed reports;
* exports to other systems;
* automatic backups.

Each of those elements is also part of personal data processing.

And all must be considered when an organization assesses its level of compliance.

## Software Tells a Story

An application developed over several years reflects the organization's history.

Each new feature adds processes.

Each integration incorporates new data journeys.

Each improvement leaves a mark on the architecture.

Over time, the system becomes a network where the same information can travel very different paths depending on the context.

That behavior usually goes unnoticed...

Until a regulation forces you to review it.

## Understand Before Intervening

During this stage, we understood that any significant modification had to be supported by a much deeper understanding of the system.

For that reason, we devoted a significant part of the work to building a map of the personal data journey.

We were not only looking to identify where it was stored.

We wanted to understand how it traveled.

Which processes used it.

Which components depended on it.

And what impact modifying any of those journeys would have.

That work was developed as part of a research specification within our engineering process, whose goal was to build evidence before defining solutions.

## Complexity Does Not Depend on System Size

Perhaps the most important learning was this.

It does not matter if an application has ten modules or a hundred.

What is really complex is the number of relationships that exist between them.

Each integration.

Each document.

Each automation.

Each history.

Each audit process.

Everything contributes to increasing the complexity of personal data processing.

And that complexity rarely becomes apparent until someone decides to investigate it systematically.

## What Comes Next

Once the data journey was understood, a new question emerged.

How do you convert all the obligations of a law into technical requirements that can be implemented, verified, and maintained during the system's evolution?

That was the next challenge.

And it will be the topic of the next article in this series.
MD,
            ],
            'requisitos-regulatorios-a-arquitectura' => [
                'excerpt_en' => 'How do you transform a legal text into a technical solution? In this fourth chapter of the "Building Compliance" series, I explore the process that connects a regulatory obligation with requirements, architectural decisions, components, traceability, and testing. A journey that demonstrates that the real challenge is not in programming, but in designing systems capable of complying and demonstrating their compliance.',
                'content_en' => <<<'MD'
# Turning a Law into Software Architecture

*Series: Building Compliance – Chapter 4*

So far, we have traveled a path that began long before writing code.

First, we understood that Ley 21.719 represents a software engineering challenge.

Then we researched the existing system.

Later, we discovered how personal data traveled through multiple processes, components, and documents.

But there was still a fundamental question.

**How do you transform a legal obligation into a software solution?**

The answer is not programming.

It is designing.

## The Law Does Not Speak the Language of Software

Laws are written to establish principles, rights, and obligations.

Software, on the other hand, is built through components, interfaces, data models, services, and processes.

Between both worlds there is an enormous difference.

The law will never say:

*"Add a new table."*

Nor:

*"Implement this endpoint."*

What it establishes are obligations that must be interpreted and subsequently transformed into technical decisions.

That space between regulation and implementation is where engineering truly happens.

## The First Step: Transforming Obligations into Requirements

During the research, we understood that it was not possible to design solutions directly from the legal text.

First, it was necessary to identify what each obligation meant for the system we were studying.

For example, a legal obligation could be translated into questions like:

* Which process needs to be modified?
* What information must be recorded?
* What evidence must be retained?
* Who will be responsible for each action?
* What restrictions must be applied?

Only after answering those questions did the first technical requirements emerge.

They were not invented requirements.

They were the translation of a regulatory obligation into the specific context of the system.

## Requirements Start Modifying the Architecture

When several requirements appear simultaneously, something interesting happens.

They stop affecting isolated features.

They start modifying the architecture.

A new control can impact:

* authentication;
* authorization;
* auditing;
* storage;
* document generation;
* integrations with other systems;
* notification services.

At that point, it ceases to be a functional change.

It becomes an architectural decision.

## Components Also Evolve

The architecture does not change only because new modules appear.

Often, existing components need to take on new responsibilities.

A service that previously only stored information may now also need to:

* record evidence;
* control access;
* apply retention rules;
* anonymize information;
* respond to requests related to data subject rights.

That means the impact is rarely contained within a single feature.

It usually spans multiple system components.

## The Importance of Traceability

During this process, a need emerged that we had not initially considered.

Each decision had to answer a very simple question.

**Why does this change exist?**

The answer could not be:

*"Because someone asked for it."*

It had to be fully reconstructible.

Legal obligation.

↓

Research finding.

↓

Requirement.

↓

Architectural decision.

↓

Implementation.

↓

Test.

That journey makes it possible to understand the origin of any modification, even months after it was developed.

And, above all, it prevents decisions from losing their context over time.

## Without Tests, There Is No Trust

There is another common mistake.

Thinking that implementing a feature means solving the problem.

In reality, implementation only demonstrates that the code was written.

It does not demonstrate that the obligation is being met.

For that reason, every important decision should end with mechanisms that allow verifying its operation.

Depending on the case, that evidence can take different forms:

* automated tests;
* audits;
* event logs;
* functional validations;
* technical documentation;
* operational evidence.

Implementation represents only a part of the work.

The ability to demonstrate its operation is equally important.

## Architecture Becomes a Bridge

At the end of this stage, we understood something that changed our approach to the project.

Architecture is not simply a set of diagrams.

It is the bridge between a legal obligation and a verifiable system behavior.

It makes it possible to transform abstract concepts into concrete components.

It relates requirements to implementations.

And it maintains coherence among all decisions made during the software's evolution.

## Beyond a Law

Although this work originated during the adaptation of a system to Ley 21.719, the process can be applied to any high-impact regulatory change.

Security regulations.

Quality requirements.

International standards.

Data governance.

Sector compliance.

All present the same challenge.

Transforming external obligations into internal engineering decisions.

And that transformation does not happen by writing code.

It happens by designing architecture.

## What Comes Next

So far, we have talked about research, evidence, architecture, and technical decisions.

But there is still a widely held myth.

That complying with a regulation means adding a terms and conditions acceptance checkbox.

In the next article, we will see why that idea is far from the reality and what it actually implies to adapt a system to respond to a data protection regulation.
MD,
            ],
            'diseno-capacidades-cumplimiento' => [
                'excerpt_en' => 'Is consent enough to comply with Ley 21.719? In this fifth chapter of the "Building Compliance" series, I analyze why that idea is one of the most common myths about data protection. Minimization, retention, the right to be forgotten, portability, traceability, auditing, incident management, security, and anonymization are part of a much broader engineering challenge than a simple acceptance checkbox.',
                'content_en' => <<<'MD'
# Why Complying with Ley 21.719 Requires Much More Than Adding a Checkbox

*Series: Building Compliance – Chapter 5*

There is an image that appears again and again when personal data protection is discussed.

A form.

A checkbox.

And text that says:

*"I accept the terms and conditions."*

For many people, that small box represents compliance with the regulation.

However, after studying Ley 21.719 and analyzing a real application, we reached a very different conclusion.

Consent is important.

But it is far from sufficient.

## The Checkbox Myth

When an organization begins preparing for a new regulation, a seemingly simple question often arises:

*"Where do we add consent?"*

It is a logical question.

But it starts from a wrong premise.

Personal data protection does not begin when the user accepts something.

It begins much earlier.

It begins when the system decides what data to collect, how to store it, who can access it, how long to retain it, and what happens when a data subject exercises their rights.

The checkbox is just one small piece within a much larger system.

## The Principle of Minimization

One of the first exercises we carried out was to review the information the application stored.

The question was not:

*"Can we store this data?"*

The question was different.

**Do we really need to store it?**

That change in perspective is very powerful.

For years, many applications grew by adding new fields because they "might be useful in the future."

Today, the correct question is exactly the opposite.

What information is strictly necessary to fulfill the purpose of the system?

Every unnecessary piece of data represents an additional responsibility.

## Retaining Information Also Has a Cost

Another very common assumption is to think that retaining information indefinitely is a good practice.

From a technical perspective, it seems convenient.

More data.

More history.

More analytical capability.

But data protection forces you to ask something different.

How long do we need to retain this information?

Retaining personal data indefinitely does not always add value.

Instead, it increases the organization's risk surface and responsibilities.

## The Right to Be Forgotten Is a Technical Challenge

One of the most complex questions that arose during the research was surprisingly simple.

**If a person requests the deletion of their data, can we actually do it?**

Answering that question involves much more than executing a `DELETE`.

You need to understand:

* what data can be deleted;
* what must be retained due to legal obligations;
* where copies exist;
* what documents have already been generated;
* what backups contain that information;
* what records are part of audits or histories.

Deleting information can become one of the most complex processes within an existing system.

## Portability Also Requires Architecture

Another right recognized by the regulation is the possibility of obtaining personal data in a usable format.

At first glance, it seems like a simple export.

But the complexity appears again.

Data rarely lives in one place.

Generating complete, consistent, and understandable information requires integrating multiple system components.

Portability is not just a report.

It is an integration process.

## Without Traceability, There Is No Verifiable Compliance

During our research, an idea emerged that ended up becoming a design principle.

It is not enough to do things correctly.

We must also be able to demonstrate it.

That requires traceability.

We need to know:

* who performed an action;
* when it occurred;
* what information was modified;
* what process originated it;
* what evidence was recorded.

Traceability ceases to be a support tool.

It becomes an essential mechanism for demonstrating system operation.

## Auditing Is No Longer Optional

Many organizations incorporate audits only when a regulation requires them.

But data protection changes that logic.

Auditing makes it possible to reconstruct events.

Understand decisions.

Investigate incidents.

Verify accesses.

And support compliance processes.

In other words, it ceases to be an administrative feature.

It becomes part of the system architecture.

## Preparing for Incidents

There is another question that rarely arises during development.

**What happens when something goes wrong?**

Every organization hopes that a personal data incident will never occur.

However, systems must be prepared to respond when it happens.

That means having mechanisms that allow:

* detecting events;
* identifying the scope;
* reconstructing what happened;
* assessing the impact;
* acting in a timely manner.

Incident management begins long before the first incident occurs.

It begins during software design.

## Security Is Not a Feature

We often talk about "adding security" as if it were just another module.

The reality is different.

Security spans the entire architecture.

It is present in:

* authentication;
* authorization;
* encryption;
* communications;
* storage;
* logging;
* infrastructure;
* monitoring.

It cannot be added at the end of the project.

It must be part of every important decision.

## Anonymization Is Also Design

Another important learning was understanding that anonymizing information does not simply mean hiding names.

Depending on the context, it may require transforming, replacing, or removing data in a way that no longer allows identifying a person.

That affects processes, reports, statistical analyses, and data models.

Anonymization is also part of the architecture.

## Much More Than a Feature

After going through this process, we understood that adapting to Ley 21.719 does not mean adding a set of new screens.

It means reviewing how the system was built.

How information flows.

Which architectural decisions remain valid.

And which ones need to evolve.

The checkbox exists.

Consent also exists.

But they represent only a small part of a much greater challenge.

The real transformation happens when compliance stops being an isolated feature and becomes a cross-cutting characteristic of the entire architecture.

## What Comes Next

So far, we have primarily discussed the problem and the process for addressing it.

In the next chapter, I will show how we are taking these ideas to a concrete case, sharing the lines of work we are developing to adapt SIGES and how a real project becomes an engineering laboratory for building reusable solutions in other systems.
MD,
            ],
            'herramientas-razonamiento-cumplimiento' => [
                'excerpt_en' => 'How do you translate research on data protection into concrete changes on a system? In this sixth chapter of the "Building Compliance" series, I present SIGES as a case study and explain the main lines of work we are developing: personal data inventory and classification, consent management, rights exercise, traceability, auditing, and data governance. More than isolated features, they are part of an architectural evolution oriented to building systems prepared for regulatory compliance.',
                'content_en' => <<<'MD'
# What Happens When We Take All This Research to a Real System

### Case Study: SIGES

*Series: Building Compliance – Chapter 6*

In recent articles, I have shared the path we followed to study Ley 21.719 from a software engineering perspective.

We talked about research.

About evidence.

About architecture.

About traceability.

And about why compliance cannot be reduced to a new screen or a consent checkbox.

But every research effort inevitably reaches the same point.

You have to build a solution.

In our case, that engineering laboratory is **SIGES**, a platform for medical equipment management that is evolving to respond to the new challenges of personal data protection.

The goal was never to turn SIGES into a product that "complies with the law."

The goal was much more ambitious.

To build an architecture prepared to evolve along with future regulatory requirements.

## Compliance Is Not a Module

One of the first learnings was understanding that regulatory compliance cannot be implemented as an isolated feature.

There is no "compliance module" that solves the problem.

Data protection spans virtually the entire application.

It affects processes.

Data models.

Permissions.

Documents.

Integrations.

Audits.

And the way each component interacts with the others.

For that reason, we decided to approach the adaptation as an architectural evolution and not as a set of independent features.

## Building a Personal Data Inventory

Every data protection strategy begins with a very simple question.

**What personal data does the system actually process?**

Answering it requires much more than reviewing the database.

It involves identifying what information is collected, where it is stored, what processes use it, and what its purpose is within the application.

For that reason, one of the first components we are developing is a personal data inventory.

Not as a static document.

But as a tool that makes it possible to understand information processing as the system evolves.

## Classify Before Protecting

Not all information has the same level of sensitivity.

Some data requires stricter controls than others.

That reality led us to incorporate classification mechanisms that make it possible to identify the type of information processed and facilitate the application of measures appropriate to its nature.

Classification ceases to be a documentary exercise.

It becomes useful information for the architecture.

## Managing Consent as Part of the System

Consent remains important.

But we decided to approach it as a process and not as a simple initial acceptance.

That means recording when it was granted, under what conditions, for what purpose, and how to manage potential changes over time.

Consent management is part of the personal data lifecycle and must be naturally integrated with the rest of the application.

## Preparing the System for Rights Exercise

One of the main requirements of Ley 21.719 is that individuals may exercise rights over their personal data.

That poses significant technical challenges.

The system must be able to locate information related to a data subject, understand where it is located, and facilitate processes that allow timely response to legitimate requests.

To that end, we are incorporating mechanisms that help identify, relate, and manage information distributed across different application components.

## Traceability as a Design Principle

Throughout the research, an idea emerged that ended up becoming an architectural principle.

Every important decision must leave evidence.

That means recording the origin of certain changes, understanding the information journey, and maintaining a clear relationship between processes, data, and actions performed.

Traceability ceases to be a support mechanism.

It becomes an essential system property.

## Evidence-Oriented Auditing

An audit is not just about recording events.

Its true value appears when it makes it possible to reconstruct what happened.

For that reason, we are strengthening audit mechanisms so that the recorded information can support analysis, review, and demonstration processes of system operation.

Evidence becomes an architecture asset.

## Data Governance from Engineering

One of the concepts that gained the most strength during this process was data governance.

Not as an administrative policy.

But as a set of technical capabilities that make it possible to manage the information lifecycle consistently.

Understanding what data exists.

Who can access it.

What purpose it serves.

How it evolves.

And what controls protect it.

From our perspective, data governance ceases to be an institutional document and becomes part of software design.

## Much More Than an Adaptation

Over the months, we understood that the project was changing its nature.

We had started by trying to adapt an application to a new regulation.

We ended up building an architectural foundation that will make it much easier to incorporate future regulatory requirements.

That change in approach is probably one of the greatest learnings from this entire experience.

We are not just developing features.

We are strengthening the system's capacity to evolve.

## An Experience That Can Be Reused

Although this work is being developed on SIGES, many of the questions we resolved are common to any organization that maintains information systems with personal data.

How to build a data inventory?

How to classify information?

How to manage consent?

How to respond to rights exercise?

How to maintain traceability and auditing?

How to incorporate data governance principles without completely redesigning the system?

We believe that the answers obtained during this process can serve as a starting point for other projects facing similar challenges.

## What Comes Next

In the next and final article of this first stage, I will share perhaps the most important learning from this entire experience.

How a research effort initiated to adapt a system ended up becoming a reusable methodology to help other organizations face their own transformation processes in response to Ley 21.719 and future regulations.
MD,
            ],
            'procesos-segundo-orden' => [
                'excerpt_en' => 'What does it mean to adapt a healthcare system to the new Data Protection Law? In this seventh chapter of the "Building Compliance" series, I share the main learnings obtained during the evolution of a healthcare sector system: the importance of trust, evidence, documentation, and architecture when working with especially sensitive information. More than a compliance project, it ended up being a lesson in responsible engineering.',
                'content_en' => <<<'MD'
# What We Learned Adapting a Healthcare System to the New Data Protection Law

*Series: Building Compliance – Chapter 7*

Throughout this series, I have shared how we approached adapting an existing system to Ley 21.719 from a software engineering perspective.

We talked about research.

About evidence.

About architecture.

About traceability.

And about how a legal obligation ends up becoming technical decisions.

But there is an aspect that deserves its own reflection.

The fact that the system belongs to the healthcare domain.

Because when a system manages information related to people's care, data protection ceases to be solely a legal obligation.

It also becomes an ethical responsibility.

## In Healthcare, There Is No "Unimportant" Data

In many information systems, it is possible to classify certain data as secondary.

In a healthcare system, that distinction is much more difficult.

A name.

An identification.

A maintenance request.

A work order.

A technical report.

A photograph.

A signed document.

Individually, they may seem like administrative elements.

But together, they describe processes, people, organizations, and decisions that are part of a healthcare institution's operation.

Understanding that context changed our way of analyzing the software.

## Trust Is Also Part of the System

When a healthcare organization uses a technology platform, it entrusts it with much more than information.

It entrusts it with trust.

It trusts that data will be available when needed.

It trusts that only authorized individuals will access it.

It trusts that there will be sufficient records to understand what happened in the event of any incident.

And it trusts that the system will evolve without compromising the information it manages.

That trust is not built solely with infrastructure or security controls.

It is also built through engineering decisions.

## We Discovered That Data Protection Spans the Entire Operation

When we began the research, we expected to find some processes clearly related to data protection.

The reality was different.

Information protection appeared practically everywhere.

In user management.

In documents generated by the application.

In historical records.

In notifications.

In integrations.

In audits.

In backups.

And in many processes that we initially did not associate with regulatory compliance.

We understood that it was not possible to isolate the problem.

Data protection is part of the system's complete operation.

## Every Decision Has Consequences

One of the greatest learnings was understanding that even apparently small changes can have significant effects.

Modifying an authentication process.

Changing the way documents are generated.

Updating an audit mechanism.

Deleting information.

Incorporating new controls.

Each of those decisions can affect multiple application components.

For that reason, we learned to distrust quick solutions.

The best decisions were those supported by research and evidence.

## Documentation Ceased to Be a Requirement

When we started the project, we saw documentation as support for development.

Today, we see it differently.

Documentation makes it possible to understand the system.

Explain decisions.

Relate requirements to implementations.

Facilitate audits.

And preserve knowledge when the software continues to evolve.

In a project of this nature, documenting ceased to be an administrative task.

It became part of the engineering process.

## Evidence Generates Trust

Another important learning was understanding that claims have little value if they cannot be demonstrated.

It is not enough to say that a process is secure.

You need to explain why.

It is not enough to claim that traceability exists.

You need to show how it is obtained.

It is not enough to indicate that the system evolved.

You need to demonstrate what changed and why.

Evidence ended up becoming one of the project's most important assets.

## Technology Changes. Principles Remain.

Over the past few years, languages, frameworks, architectures, and tools have changed.

However, this project confirmed something that will probably remain true for a long time.

The principles of good engineering endure.

Understand before intervening.

Support decisions with evidence.

Maintain traceability.

Document knowledge.

Design thinking about the system's evolution.

Those principles continue to be valid regardless of the technology used.

## More Than Adapting a System

When this work began, we thought our goal was to adapt an application to a new regulation.

Today, we see the project differently.

We are learning how to build systems capable of evolving responsibly.

And that experience is probably much more valuable than any specific feature we can develop.

## An Experience That Transcends the Healthcare Sector

Although this project is being developed on a system for medical equipment management, many of the challenges found are common to organizations in other sectors.

Every application that processes personal data faces similar questions.

How to understand the information journey?

How to demonstrate compliance?

How to evolve without losing control of the architecture?

How to transform regulatory obligations into sustainable technical decisions?

For that reason, we believe that the lessons learned during this process can serve as a reference for any organization that must adapt existing systems to new regulatory frameworks.

## What Comes Next

In the next article, I will close this first season by sharing the most important conclusion from this entire journey.

How a research effort initiated to solve a specific problem ended up becoming a different way of approaching software evolution in the face of increasingly complex regulations.
MD,
            ],
            'ingenieria-datos-transformacion-regular' => [
                'excerpt_en' => 'Adapting to Ley 21.719 presents common challenges for organizations of all sectors. In the final chapter of "Building Compliance," I reflect on how the experience gained from studying and evolving a real system can be transformed into a reusable methodology for diagnostics, consulting, and training. More than a solution for a specific product, it is a way of turning complex regulations into evidence-based engineering decisions.',
                'content_en' => <<<'MD'
# How This Experience Can Help Other Organizations

*Series: Building Compliance – Chapter 8 (Season Finale)*

When I published the first article in this series, I presented an idea that might seem provocative:

> **Ley 21.719 is not only a legal project. It is a software engineering project.**

Eight articles later, that idea is no longer a hypothesis.

It is a conclusion supported by research, evidence, and the experience of adapting a real system.

During this journey, we did not just study a regulation.

We learned a different way of facing complex changes on existing software.

And perhaps that is the most valuable result of the entire project.

## The Challenge Belongs to More Than Just the Healthcare Sector

Although the case study I shared corresponds to SIGES, a system for medical equipment management, we quickly understood that the questions we were answering were common to virtually any organization.

It does not matter if the software belongs to a logistics company, a university, a municipality, an industry, or a financial institution.

If it processes personal data, it will face similar questions.

What information does it actually store?

Where is it located?

How does it flow between different processes?

Which components depend on it?

How to demonstrate that the processing complies with the regulation?

Technology changes.

The questions remain.

## The Real Problem Is Rarely the Law

It is often thought that the main obstacle is correctly interpreting a new regulation.

Our experience was different.

The law establishes principles and obligations.

The real challenge appears when an organization tries to answer questions about its own system.

Often, no one can say with certainty:

* what personal data exists;
* where it is stored;
* who can access it;
* how it relates to different modules;
* how long it remains available;
* what evidence supports current processes.

Without that information, it is very difficult to make decisions with confidence.

## Before Implementing, It Is Necessary to Understand

One of the main conclusions of this series is that implementation should not be the starting point.

Before modifying an application, it is worth building knowledge.

Understand the system.

Identify the data journey.

Relate legal obligations to actual processes.

Document findings.

And establish a baseline that makes it possible to measure evolution.

The experience showed that investing time in understanding the problem reduces uncertainty and improves the quality of subsequent decisions.

## A Methodology That Can Be Reused

As we progressed in the research, something happened that we had not anticipated.

The process began to become independent of the project that originated it.

The questions.

The evidence collection.

The findings.

The baseline construction.

The traceability of decisions.

All that work could be applied to other systems.

We understood that we were not just developing an adaptation for SIGES.

We were building a methodology for studying existing systems before intervening them.

A methodology that helps transform regulatory requirements into verifiable engineering decisions.

## A Starting Point for Other Organizations

Many organizations will have to face exactly the same challenge: understanding what personal data they have, how it flows within their systems, and what changes they must implement to comply with the new regulation.

Not all start from the same place.

Some have updated documentation.

Others work on applications developed over years, with multiple integrations and little formal knowledge of their architecture.

Precisely for that reason, we believe that the approach we have developed can serve as a base for diagnostic, consulting, and training processes oriented to building evidence before implementing changes.

It does not offer universal answers.

It offers a structured way to find them.

## Beyond Ley 21.719

Perhaps the most important learning from this entire experience is that this process does not end with a regulation.

Organizations will continue facing new requirements.

Regulatory changes.

New standards.

Security requirements.

Interoperability demands.

New technological risks.

The capacity to research a system, understand it, and evolve it in a controlled manner will probably be one of the most valuable competencies of software engineering in the coming years.

## The Purpose of This Series

This series was never intended to explain Ley 21.719 article by article.

Nor to demonstrate that a system complies or does not comply with a regulation.

The purpose was to share an engineering experience.

To show that before modifying software, it is possible to research.

That decisions can be supported by evidence.

That architecture can become a bridge between regulation and implementation.

And that documenting that journey generates reusable knowledge.

If any of these articles managed to change the way you observe existing systems, then the series has already fulfilled its purpose.

## What Lies Ahead

The adaptation of SIGES continues.

Research also continues.

New questions, new challenges, and new decisions to document will keep emerging.

I hope to share that journey in future publications, because I believe that one of the best ways to strengthen our profession is to show how we make decisions, how we learn from them, and how that knowledge can help others.

Personal data protection will continue to evolve.

And software engineering will have to evolve as well.

Perhaps that is the true message left by this first season of **"Building Compliance."**

It is not just about building systems that work.

It is about building systems capable of evolving responsibly.
MD,
            ],
        ];

        foreach ($posts as $slug => $data) {
            $post = Post::where('slug', $slug)->first();
            if (! $post) {
                continue;
            }
            $post->update([
                'excerpt' => [
                    'es' => $post->excerpt['es'] ?? '',
                    'en' => $data['excerpt_en'],
                ],
                'content' => [
                    'es' => $post->content['es'] ?? '',
                    'en' => $data['content_en'],
                ],
            ]);
        }
    }

    private function updateProjects(): void
    {
        $projects = [
            'adaptacion-ley-21719-ingenieria-evidencia' => [
                'summary_en' => 'Adapting an existing system to Ley 21.719 begins long before writing code. This case study shows how to approach regulation from the perspective of software engineering, using research, evidence, and architecture to understand the system before planning its evolution.',
                'description_en' => <<<'MD'
# Case Study: Adapting Existing Software to Ley 21.719 Through Evidence-Based Engineering

## Problem

The entry into force of Ley 21.719 represents a challenge for any organization that develops or maintains systems that process personal data. In many cases, these systems were built years before the regulation existed, so adapting them requires understanding how information flows, identifying risks, and planning changes on an existing architecture.

## Approach

Instead of approaching the regulation solely from a legal perspective or implementing isolated changes, I decided to treat the problem as a software engineering project.

The first step was to research the system before modifying it, collecting evidence about its operation and establishing a baseline that made it possible to understand the impact of future technical decisions.

This work is part of the evolution of SIGES, a system for medical equipment management used as a case study to develop an evidence-based adaptation strategy.

## Contribution

This case study demonstrates how a regulation can be transformed into a structured process of research, analysis, and architectural evolution, reducing uncertainty before starting development.

## What This Work Demonstrates

* Ability to analyze existing systems before intervening on them.
* Application of evidence-based research to support engineering decisions.
* Architectural approach to adapting software to new regulations.
* Planning of changes on applications in production.
MD,
                'problem_en' => 'The entry into force of Ley 21.719 represents a challenge for any organization that develops or maintains systems that process personal data. In many cases, these systems were built years before the regulation existed, so adapting them requires understanding how information flows, identifying risks, and planning changes on an existing architecture.',
                'approach_en' => "Instead of approaching the regulation solely from a legal perspective or implementing isolated changes, I decided to treat the problem as a software engineering project.\n\nThe first step was to research the system before modifying it, collecting evidence about its operation and establishing a baseline that made it possible to understand the impact of future technical decisions.\n\nThis work is part of the evolution of SIGES, a system for medical equipment management used as a case study to develop an evidence-based adaptation strategy.",
                'contribution_en' => 'This case study demonstrates how a regulation can be transformed into a structured process of research, analysis, and architectural evolution, reducing uncertainty before starting development.',
                'what_it_demonstrates_en' => "* Ability to analyze existing systems before intervening on them.\n* Application of evidence-based research to support engineering decisions.\n* Architectural approach to adapting software to new regulations.\n* Planning of changes on applications in production.",
            ],
            'investigacion-evidencia-ley-21719' => [
                'summary_en' => 'Before modifying SIGES to adapt it to Ley 21.719, a structured investigation of the system was conducted using an evidence-based approach. Through research questions, findings, technical evidence, baselines, and documented decisions, it was possible to understand the application\'s behavior and plan its evolution with objective foundations. This case study demonstrates the value of researching before developing.',
                'description_en' => <<<'MD'
# Case Study: Evidence-Based Research to Adapt Software to Ley 21.719

## Problem

Adapting an existing system to a new regulation usually begins directly with feature development. However, when the actual impact of the changes is unknown, there is a high risk of introducing incomplete, inconsistent, or difficult-to-maintain modifications.

How do you plan an architectural evolution without first understanding the system you intend to modify?

## Approach

Before writing a single line of code, we decided to research.

The adaptation of SIGES began with a systematic process of knowledge gathering about the existing application, using an evidence-based engineering approach.

The work was organized through research questions, identification of findings, technical evidence collection, baseline construction, and explicit recording of decisions derived from the analysis.

This process made it possible to understand the system's actual behavior before defining any implementation strategy.

## Contribution

This case study demonstrates how a research-oriented approach can reduce uncertainty in software evolution projects, providing an objective basis for technical and architectural decision-making.

More than producing documentation, the goal was to build reusable knowledge about the system.

## What This Work Demonstrates

* Ability to research existing systems before intervening on them.
* Application of a structured process for collecting technical evidence.
* Construction of baselines that allow measuring software evolution.
* Traceability between research questions, evidence, findings, and engineering decisions.
* Planning of changes supported by verifiable information.
MD,
                'problem_en' => "Adapting an existing system to a new regulation usually begins directly with feature development. However, when the actual impact of the changes is unknown, there is a high risk of introducing incomplete, inconsistent, or difficult-to-maintain modifications.\n\nHow do you plan an architectural evolution without first understanding the system you intend to modify?",
                'approach_en' => "Before writing a single line of code, we decided to research.\n\nThe adaptation of SIGES began with a systematic process of knowledge gathering about the existing application, using an evidence-based engineering approach.\n\nThe work was organized through research questions, identification of findings, technical evidence collection, baseline construction, and explicit recording of decisions derived from the analysis.\n\nThis process made it possible to understand the system's actual behavior before defining any implementation strategy.",
                'contribution_en' => "This case study demonstrates how a research-oriented approach can reduce uncertainty in software evolution projects, providing an objective basis for technical and architectural decision-making.\n\nMore than producing documentation, the goal was to build reusable knowledge about the system.",
                'what_it_demonstrates_en' => "* Ability to research existing systems before intervening on them.\n* Application of a structured process for collecting technical evidence.\n* Construction of baselines that allow measuring software evolution.\n* Traceability between research questions, evidence, findings, and engineering decisions.\n* Planning of changes supported by verifiable information.",
            ],
            'analisis-recorrido-datos-personales' => [
                'summary_en' => 'Adapting a system to a new regulation requires understanding how personal data flows within the application. This case study shows the analysis conducted on SIGES to identify the information\'s journey through users, requests, reports, documents, histories, and audits. The findings made it possible to establish an objective basis for planning the system\'s architectural evolution.',
                'description_en' => <<<'MD'
# Case Study: Analyzing the Journey of Personal Data in an Existing System

## Problem

One of the main difficulties in adapting a system to Ley 21.719 is understanding where personal data actually resides and how it relates to the different processes of the application.

In systems with years of evolution, information rarely remains in a single location. A single piece of data can appear in multiple modules, documents, histories, and integrations, making it very difficult to estimate the impact of a modification or respond to new regulatory requirements.

## Approach

As part of the research conducted on SIGES, a systematic analysis of personal data processing within the application was carried out.

The goal was not to catalog tables or review only the database, but to understand the complete journey of information through the system's different processes.

During the analysis, it was identified that a single piece of personal data could be present in different functional contexts, among them:

* User management.
* Technical requests.
* Technical reports.
* System-generated emails.
* PDF documents.
* Historical records.
* Audits.

This finding made it possible to understand that regulatory compliance had to be addressed as a cross-cutting architectural evolution and not as isolated modifications to specific features.

## Contribution

This case study demonstrates the importance of analyzing the complete data flow before planning regulatory changes.

Understanding how information flows makes it possible to identify dependencies, reduce risks, and design consistent solutions for production systems.

## What This Work Demonstrates

* Ability to analyze complex applications from the perspective of data processing.
* Identification of functional dependencies between different system components.
* Understanding of the personal data lifecycle.
* Impact analysis prior to architectural evolution processes.
* Application of evidence-based research on existing systems.
MD,
                'problem_en' => "One of the main difficulties in adapting a system to Ley 21.719 is understanding where personal data actually resides and how it relates to the different processes of the application.\n\nIn systems with years of evolution, information rarely remains in a single location. A single piece of data can appear in multiple modules, documents, histories, and integrations, making it very difficult to estimate the impact of a modification or respond to new regulatory requirements.",
                'approach_en' => "As part of the research conducted on SIGES, a systematic analysis of personal data processing within the application was carried out.\n\nThe goal was not to catalog tables or review only the database, but to understand the complete journey of information through the system's different processes.\n\nDuring the analysis, it was identified that a single piece of personal data could be present in different functional contexts, among them:\n\n* User management.\n* Technical requests.\n* Technical reports.\n* System-generated emails.\n* PDF documents.\n* Historical records.\n* Audits.\n\nThis finding made it possible to understand that regulatory compliance had to be addressed as a cross-cutting architectural evolution and not as isolated modifications to specific features.",
                'contribution_en' => "This case study demonstrates the importance of analyzing the complete data flow before planning regulatory changes.\n\nUnderstanding how information flows makes it possible to identify dependencies, reduce risks, and design consistent solutions for production systems.",
                'what_it_demonstrates_en' => "* Ability to analyze complex applications from the perspective of data processing.\n* Identification of functional dependencies between different system components.\n* Understanding of the personal data lifecycle.\n* Impact analysis prior to architectural evolution processes.\n* Application of evidence-based research on existing systems.",
            ],
            'transformacion-requisitos-regulatorios-arquitectura' => [
                'summary_en' => 'Laws do not describe how to build software. This case study shows the process used to transform the obligations of Ley 21.719 into software requirements, architectural decisions, components, traceability mechanisms, and validation strategies. The result was a technical base that connects regulation with implementation through a structured engineering process.',
                'description_en' => <<<'MD'
# Case Study: Transforming Regulatory Requirements into Software Architecture

## Problem

Regulations establish obligations, principles, and responsibilities, but do not indicate how they should be implemented in an information system.

For development teams, the challenge lies not only in understanding the text of a law, but in translating its requirements into technical requirements, architectural decisions, and verifiable changes to an existing application.

How do you convert a legal obligation into an engineering solution?

## Approach

As part of the process of adapting SIGES to Ley 21.719, an analysis was conducted to transform regulatory obligations into concrete software design elements.

Each identified obligation was evaluated to determine:

* What functional or non-functional requirements it generated.
* What system processes were affected.
* What components needed to evolve.
* What traceability mechanisms would be necessary.
* How to subsequently validate the implementation through evidence and tests.

This approach made it possible to build an explicit relationship between regulation, architecture, and future development activities.

## Contribution

This case study demonstrates that software architecture can act as a bridge between a regulation and its technical implementation.

The systematic transformation of legal obligations into engineering decisions facilitates change planning, improves project traceability, and reduces the possibility of implementing isolated or inconsistent solutions.

## What This Work Demonstrates

* Translation of regulatory requirements into software requirements.
* Compliance-oriented architectural design.
* Identification of components affected by cross-cutting changes.
* Definition of traceability mechanisms between regulation, requirements, and implementation.
* Planning of validation strategies through evidence and tests.
MD,
                'problem_en' => "Regulations establish obligations, principles, and responsibilities, but do not indicate how they should be implemented in an information system.\n\nFor development teams, the challenge lies not only in understanding the text of a law, but in translating its requirements into technical requirements, architectural decisions, and verifiable changes to an existing application.\n\nHow do you convert a legal obligation into an engineering solution?",
                'approach_en' => "As part of the process of adapting SIGES to Ley 21.719, an analysis was conducted to transform regulatory obligations into concrete software design elements.\n\nEach identified obligation was evaluated to determine:\n\n* What functional or non-functional requirements it generated.\n* What system processes were affected.\n* What components needed to evolve.\n* What traceability mechanisms would be necessary.\n* How to subsequently validate the implementation through evidence and tests.\n\nThis approach made it possible to build an explicit relationship between regulation, architecture, and future development activities.",
                'contribution_en' => "This case study demonstrates that software architecture can act as a bridge between a regulation and its technical implementation.\n\nThe systematic transformation of legal obligations into engineering decisions facilitates change planning, improves project traceability, and reduces the possibility of implementing isolated or inconsistent solutions.",
                'what_it_demonstrates_en' => "* Translation of regulatory requirements into software requirements.\n* Compliance-oriented architectural design.\n* Identification of components affected by cross-cutting changes.\n* Definition of traceability mechanisms between regulation, requirements, and implementation.\n* Planning of validation strategies through evidence and tests.",
            ],
            'diseno-capacidades-cumplimiento' => [
                'summary_en' => 'Complying with Ley 21.719 requires much more than adding a consent mechanism. This case study shows how the analysis of SIGES made it possible to identify architectural capabilities related to minimization, retention, rights exercise, portability, traceability, auditing, security, and anonymization, approaching compliance as a cross-cutting system characteristic.',
                'description_en' => <<<'MD'
# Case Study: Designing Compliance Capabilities for Information Systems

## Problem

One of the most common mistakes when adapting a system to a new data protection regulation is reducing compliance to the addition of one or two visible features, such as a consent form or an acceptance checkbox.

However, regulatory obligations affect multiple aspects of the personal data lifecycle and require a much deeper evolution of the system architecture.

How do you design a solution that comprehensively addresses these new requirements?

## Approach

During the analysis conducted to adapt SIGES to Ley 21.719, it was identified that compliance does not depend on a single feature, but on a set of capabilities that must be incorporated in a coordinated manner within the application.

The study identified the need to consider, among other aspects:

* Data minimization principle.
* Retention and deletion policies.
* Data subject rights exercise.
* Information portability.
* Operation traceability.
* Process auditing.
* Incident management.
* Security controls.
* Anonymization strategies when applicable.

These capabilities were analyzed as cross-cutting architectural requirements and not as independent features.

## Contribution

This case study demonstrates that regulatory compliance must be designed as an integral system capability.

Understanding the relationships between the different data protection principles makes it possible to build more consistent, sustainable, and prepared solutions for future regulatory evolutions.

## What This Work Demonstrates

* Identification of architectural capabilities associated with regulatory compliance.
* Comprehensive analysis of the personal data lifecycle.
* Design of cross-cutting solutions for existing systems.
* Assessment of the impact of non-functional requirements on architecture.
* Planning of compliance-oriented evolution strategies.
MD,
                'problem_en' => "One of the most common mistakes when adapting a system to a new data protection regulation is reducing compliance to the addition of one or two visible features, such as a consent form or an acceptance checkbox.\n\nHowever, regulatory obligations affect multiple aspects of the personal data lifecycle and require a much deeper evolution of the system architecture.\n\nHow do you design a solution that comprehensively addresses these new requirements?",
                'approach_en' => "During the analysis conducted to adapt SIGES to Ley 21.719, it was identified that compliance does not depend on a single feature, but on a set of capabilities that must be incorporated in a coordinated manner within the application.\n\nThe study identified the need to consider, among other aspects:\n\n* Data minimization principle.\n* Retention and deletion policies.\n* Data subject rights exercise.\n* Information portability.\n* Operation traceability.\n* Process auditing.\n* Incident management.\n* Security controls.\n* Anonymization strategies when applicable.\n\nThese capabilities were analyzed as cross-cutting architectural requirements and not as independent features.",
                'contribution_en' => "This case study demonstrates that regulatory compliance must be designed as an integral system capability.\n\nUnderstanding the relationships between the different data protection principles makes it possible to build more consistent, sustainable, and prepared solutions for future regulatory evolutions.",
                'what_it_demonstrates_en' => "* Identification of architectural capabilities associated with regulatory compliance.\n* Comprehensive analysis of the personal data lifecycle.\n* Design of cross-cutting solutions for existing systems.\n* Assessment of the impact of non-functional requirements on architecture.\n* Planning of compliance-oriented evolution strategies.",
            ],
        ];

        foreach ($projects as $slug => $data) {
            $project = Project::where('slug', $slug)->first();
            if (! $project) {
                continue;
            }
            $project->update([
                'summary' => [
                    'es' => $project->summary['es'] ?? '',
                    'en' => $data['summary_en'],
                ],
                'description' => [
                    'es' => $project->description['es'] ?? '',
                    'en' => $data['description_en'],
                ],
                'problem' => [
                    'es' => $project->problem['es'] ?? '',
                    'en' => $data['problem_en'],
                ],
                'approach' => [
                    'es' => $project->approach['es'] ?? '',
                    'en' => $data['approach_en'],
                ],
                'contribution' => [
                    'es' => $project->contribution['es'] ?? '',
                    'en' => $data['contribution_en'],
                ],
                'what_it_demonstrates' => [
                    'es' => $project->what_it_demonstrates['es'] ?? '',
                    'en' => $data['what_it_demonstrates_en'],
                ],
            ]);
        }
    }
}
